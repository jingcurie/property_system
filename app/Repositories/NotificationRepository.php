<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationRepository
{
    /**
     * 获取用户的通知列表
     *
     * @param int $userId
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNotifications(int $userId, array $filters = [])
    {
        $query = Notification::where('user_id', $userId);

        // 过滤未读
        if (!empty($filters['unread'])) {
            $query->where('is_read', false);
        }

        // 过滤优先级
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        // 过滤类型
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // 过滤未过期
        $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * 标记单条通知为已读
     */
    public function markAsRead(int $notificationId, int $userId)
    {
        return Notification::where('notification_id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => true]);
    }

    /**
     * 批量标记为已读
     */
    public function markAllAsRead(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * 删除单条通知
     */
    public function deleteNotification(int $notificationId, int $userId)
    {
        return Notification::where('notification_id', $notificationId)
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * 清空用户所有通知
     */
    public function clearAll(int $userId)
    {
        return Notification::where('user_id', $userId)->delete();
    }

    /**
     * 创建新通知
     */
    public function createNotification(array $data)
    {
        return Notification::create($data);
    }
}
