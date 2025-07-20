<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;

class SendTestNotification extends Command
{
    protected $signature = 'test:send-notification';
    protected $description = '每分钟向当前用户发送测试通知';

    public function handle()
    {
        $user = User::first(); // 用于测试，实际部署可更改为所有用户循环

        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => '测试通知',
                'content' => '系统自动发出通知，时间：' . now()->format('H:i:s'),
            ]);
        }

        $this->info('通知发送成功');
    }
}
