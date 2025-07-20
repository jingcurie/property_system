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
        $userId = Auth::id() ?? 1;

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

        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
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
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }
}
