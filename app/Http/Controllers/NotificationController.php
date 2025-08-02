<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function generate()
    {
        $notifications = [
            [
                'title' => '新租赁申请待审批',
                'content' => '请尽快处理租赁申请。',
                'type' => 'approval'
            ],
            [
                'title' => '您有一条新评论',
                'content' => '有人在您的帖子中留言。',
                'type' => 'comment'
            ],
            [
                'title' => '您收到一条站内信',
                'content' => '张三发来了一条消息。',
                'type' => 'message'
            ],
            [
                'title' => '系统公告：例行维护',
                'content' => '系统将在今晚 11 点维护，请保存好数据。',
                'type' => 'system'
            ],
            [
                'title' => '租约即将到期提醒',
                'content' => '租约将在 7 天后到期，请关注。',
                'type' => 'expire'
            ],
            [
                'title' => '水表异常告警',
                'content' => '检测到房源 A102 存在用水异常。',
                'type' => 'warning'
            ],
            [
                'title' => '其他类型通知示例',
                'content' => '请根据业务需求处理该通知。',
                'type' => 'custom'
            ],
        ];

        $item = $notifications[array_rand($notifications)];

        Notification::create([
            'user_id' => Auth::id() ?? 1,
            'title' => $item['title'],
            'content' => $item['content'],
            'is_read' => 0,
            'type' => $item['type'],
        ]);

        return response()->noContent();
    }


    public function getUnread()
    {
        // 确保用户已登录
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();

        // 通知类型映射（你可以继续调整样式）
        $typeMap = [
            'approval' => ['icon' => 'bi-check-circle-fill', 'color' => 'success'],
            'comment'  => ['icon' => 'bi-chat-dots-fill',    'color' => 'info'],
            'message'  => ['icon' => 'bi-envelope-fill',     'color' => 'primary'],
            'system'   => ['icon' => 'bi-exclamation-circle-fill', 'color' => 'secondary'],
            'expire'   => ['icon' => 'bi-clock-fill',         'color' => 'warning'],
            'warning'  => ['icon' => 'bi-exclamation-triangle-fill', 'color' => 'danger'],
            'custom'   => ['icon' => 'bi-bell-fill',          'color' => 'dark'],
        ];


        // 添加分页和限制
        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->limit(10)  // 只返回最新10条
            ->get();


        // 加上图标和颜色信息
        $notifications = $notifications->map(function ($item) use ($typeMap) {
            $typeInfo = $typeMap[$item->type] ?? ['icon' => 'bi-bell-fill', 'color' => 'secondary'];
            return [
                'id'        => $item->id,
                'title'     => $item->title,
                'content'   => $item->content,
                'is_read'   => $item->is_read,
                'type'      => $item->type,
                'created_at' => $item->created_at->toDateTimeString(),
                'icon'      => $typeInfo['icon'],
                'color'     => $typeInfo['color'],
            ];
        });

        return response()->json([
            'count' => Notification::where('user_id', $userId)->where('is_read', 0)->count(),
            'notifications' => $notifications  // 直接返回，不要再map
        ]);
    }

    // 标记单个通知为已读
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    // 标记所有通知为已读
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    // 删除通知
    public function delete($id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
    }
}
