<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    protected $notifications;

    public function __construct(NotificationRepository $notifications)
    {
        $this->notifications = $notifications;
    }

    public function index()
    {
        $notifications = $this->notifications->getUserNotifications(auth()->id());
        return response()->json($notifications);
    }

    public function markRead($id)
    {
        $this->notifications->markAsRead($id, auth()->id());
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        $this->notifications->markAllAsRead(auth()->id());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $this->notifications->deleteNotification($id, auth()->id());
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        $this->notifications->clearAll(auth()->id());
        return response()->json(['success' => true]);
    }
}
