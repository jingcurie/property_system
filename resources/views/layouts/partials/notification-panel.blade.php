
<div class="notification-panel" id="notificationPanel">
    <!-- Header -->
    <div class="panel-header">
        <h3 class="panel-title">通知</h3>
        <div class="header-actions">
            <button class="header-btn" onclick="markAllRead()">
                <i class="bi bi-check-all"></i>
            </button>
            <button class="header-btn" onclick="clearAll()">
                <i class="bi bi-trash3"></i>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <button class="filter-btn active" onclick="filterNotifications('all', this)">全部</button>
        <button class="filter-btn" onclick="filterNotifications('unread', this)">未读</button>
        <button class="filter-btn" onclick="filterNotifications('priority-2', this)">紧急</button>
        <button class="filter-btn" onclick="filterNotifications('security', this)">安全</button>
    </div>

    <!-- Notification List -->
    <div class="panel-body" id="notification-list">
        @forelse ($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? '' : 'unread' }}"
                data-id="{{ $notification->id }}" data-type="{{ $notification->type }}"
                data-priority="{{ $notification->priority }}" onclick="toggleExpand(this)">
                <div class="notification-header">
                    <div class="notification-icon priority-{{ $notification->priority }}">
                        @if ($notification->type === 'security')
                            <i class="bi bi-shield-exclamation"></i>
                        @elseif($notification->type === 'business')
                            <i class="bi bi-file-person"></i>
                        @elseif($notification->type === 'maintenance')
                            <i class="bi bi-wrench"></i>
                        @else
                            <i class="bi bi-bell"></i>
                        @endif
                    </div>
                    <div class="notification-content">
                        <h4 class="notification-title">{{ $notification->title }}</h4>
                        <p class="notification-text">{{ $notification->content }}</p>
                        <div class="notification-meta">
                            <div class="meta-left">
                                <div
                                    class="priority-dot 
                                    {{ $notification->priority == 2 ? 'high' : ($notification->priority == 1 ? 'medium' : 'low') }}">
                                </div>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <span>
                                @if ($notification->type === 'security')
                                    安全
                                @elseif($notification->type === 'business')
                                    业务
                                @elseif($notification->type === 'maintenance')
                                    维修
                                @else
                                    系统
                                @endif
                            </span>
                        </div>
                        <div class="notification-actions">
                            @if (!$notification->is_read)
                                <button class="action-btn primary"
                                    onclick="handleAction({{ $notification->id }}, event)">处理</button>
                                <button class="action-btn"
                                    onclick="markRead({{ $notification->id }}, event)">已读</button>
                            @else
                                <button class="action-btn"
                                    onclick="deleteNotification({{ $notification->id }}, event)">删除</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-bell-slash"></i></div>
                <div>暂无通知</div>
            </div>
        @endforelse
    </div>

    <!-- Footer -->
    <div class="panel-footer">
        <a href="{{ route('notifications.index') }}" class="footer-link">查看全部通知</a>
    </div>
</div>
