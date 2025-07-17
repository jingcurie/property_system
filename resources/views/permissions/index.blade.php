@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => 'Permissions',
        'pageIcon' => 'bi bi-shield-lock',
        'createUrl' => route('permissions.create'),
        'createLabel' => 'Permission',
        'searchKeywordFields' => ['name'],
        'filterFields' => [
            ['key' => 'name', 'label' => 'Module'],
        ],

        'records' => $permissions,
        'paginator' => $permissions,

        'columns' => [
            [
                'label' => 'Name',
                'field' => 'name',
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
                'field' => 'created_at',
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
