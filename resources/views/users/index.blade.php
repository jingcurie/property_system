@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => '用户列表',
        'pageIcon' => 'bi bi-people-fill',

        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => ut('modules.user.create_label'),
                    'url' => route('properties.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => ut('modules.user.export_label'),
                    'url' => route('properties.export', request()->all()),
                    'class' => 'btn btn-outline-secondary',
                ],
            ],
            'selected' => [
                [
                    'type' => 'dropdown',
                    'icon' => 'bi bi-list',
                    'label' => '批量操作',
                    'class' => 'btn btn-secondary dropdown-toggle',
                    'items' => [
                        [
                            'label' => '批量删除',
                            'action' => 'bulk-delete',
                            'icon' => 'bi bi-trash',
                        ],
                    ],
                ],
            ],
        ],
    
        'searchKeywordFields' => ['用户名', '邮箱', '角色'],
        'filterFields' => [['key' => 'name', 'label' => '角色']],
    
        'records' => $users,
        'paginator' => $users,
    
        'columns' => [
            [
                'label' => '用户名',
                'type' => 'custom',
                'render' => function ($user) {
                    $avatar = $user->avatar ?? 'default.png';
                    $avatarUrl = asset("avatars/$avatar");
                    $name = e($user->name);
    
                    return '<div class="d-flex align-items-center">' .
                        '<img src="' .
                        $avatarUrl .
                        '" class="rounded-circle me-2" style="width:48px; height:48px; object-fit:cover;">' .
                        '<span>' .
                        $name .
                        '</span>' .
                        '</div>';
                },
    
                'sortable' => true,
            ],
            [
                'label' => '邮箱',
                'column' => 'email',
            ],
            [
                'label' => '角色',
                'type' => 'custom',
                'render' => function ($user) {
                    return $user->getRoleNames()->map(function ($role) {
                            $badgeMap = [
                                'admin' => 'badge-admin',
                                'manager' => 'badge-manager',
                                'finance' => 'badge-finance',
                                'support' => 'badge-support',
                                'agent' => 'badge-agent',
                                'user' => 'badge-user',
                                'viewer' => 'badge-viewer',
                            ];
                            $class = $badgeMap[$role] ?? 'bg-secondary';
                            return "<span class=\"badge $class\">$role</span>";
                        })->implode(' ');
                },
            ],
    
            [
                'label' => '创建时间', 
                'column' => 'created_at', 
                'sortable' => false
            ],
            [
                'label' => '状态',
                'column' => 'is_active',
                'type' => 'custom',
                'render' => function ($user) {
                    $checked = $user->is_active ? 'checked' : '';
                    $toggleUrl = route('users.toggleStatus', $user->id);
    
                    return '<div class="form-check form-switch">' .
                        '<input class="form-check-input toggle-active gap-2 me-3" type="checkbox" ' .
                        $checked .
                        ' data-url="' .
                        $toggleUrl .
                        '" data-id="' .
                        $user->id .
                        '"> <span class="status-text">' . ($user->is_active ? '启用' : '禁用') .
                        '</span></div>';
                },
                'sortable' => true,
            ],
        ],
    
        'actions' => [
            [
                'label' => '编辑',
                'url' => fn($item) => route('users.edit', $item->id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => '删除',
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" . route('users.destroy', $item->id) . "')",
            ],
        ],
    
        'batchDeleteUrl' => route('users.batchDelete'),
        'routeName' => 'users.index',
        'partialsForfilter' => 'users.partials.filter_fields',
        'module' => 'users'
    ])
@endsection
