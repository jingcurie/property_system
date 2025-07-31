@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => 'Permissions',
        'pageIcon' => 'bi bi-shield-lock',
        'searchKeywordFields' => ['name'],
        'filterFields' => [
            ['key' => 'name', 'label' => 'Module'],
        ],

        'records' => $permissions,
        'paginator' => $permissions,

        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => __('property.create_label'),
                    'url' => route('properties.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => __('property.export_label'),
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

        'columns' => [
            [
                'label' => 'Name',
                'column' => 'name',
                'sortable' => true,
            ],
            [
                'label' => 'Role',
                'type' => 'custom',
                'render' => fn($item) => $item->roles->count()
                    ? $item->roles->map(fn($role) => '<span class="badge bg-secondary me-1">'.$role->name.'</span>')->implode(' ')
                    : '<span class="text-muted">—</span>',
            ],
            [
                'label' => 'Created at',
                'column' => 'created_at',
                'sortable' => true,
                'render' => fn($item) => $item->created_at->format('Y-m-d'),
            ],
        ],

        'actions' => [
            [
                'label' => 'Delete',
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" . route('permissions.destroy', $item->id) . "')",
            ],
        ],

        'batchDeleteUrl' => route('permissions.bulk-delete'),
        'routeName' => 'permissions.index',
        'partialsForfilter' => 'permissions.partials.filter_fields',
        'module' => 'permissions',
    ])
@endsection
