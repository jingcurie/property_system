@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => ut('modules.trash.page_title'),
        'pageIcon' => 'bi bi-trash',
    
        'toolbar' => [
            'default' => [],
            'selected' => [
                [
                    'type' => 'dropdown',
                    'icon' => 'bi bi-list',
                    'label' => ut('modules.trash.bulk_action'),
                    'class' => 'btn btn-secondary dropdown-toggle',
                    'items' => [
                        [
                            'label' => ut('modules.trash.bulk_restore'),
                            'action' => "restore",
                            'icon' => 'bi bi-arrow-counterclockwise',
                        ],
                        [
                            'label' => ut('modules.trash.bulk_force_delete'),
                            'action' => "force_delete",
                            'icon' => 'bi bi-x-circle',
                        ],
                    ],
                ],
            ],
        ],
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'name', // 根据模块里的“主要名称字段”适配
                'label' => ut('modules.trash.search_fields.name'),
            ],
        ],
    
        'quickFilters' => [
            [
                'key' => 'module',
                'label' => ut('modules.trash.filters.module'),
                'type' => 'select',
                'options' => [
                    'properties' => ut('modules.trash.modules.properties'),
                    'owners' => ut('modules.trash.modules.owners'),
                    'tenants' => ut('modules.trash.modules.tenants'),
                    'rentalApplications' => ut('modules.trash.modules.rentalApplications'),
                ],
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'deleted_by',
                'label' => ut('modules.trash.filters.deleted_by'),
                'type' => 'select',
                'column' => 'deleted_by',
                'options' => $deletedUsers,
            ],
            [
                'key' => 'deleted_at',
                'label' => ut('modules.trash.filters.deleted_at'),
                'type' => 'date_range',
                'column' => 'deleted_at',
            ],
        ],
    
        'records' => $records,
        'paginator' => $records,

        //columns在trash控制器中决定好了
    
        'actions' => [
            [
                'label' => ut('modules.trash.actions.restore'),
                'url' => fn($item) => 'javascript:void(0);', // 不直接跳转
                'icon' => 'bi bi-arrow-counterclockwise',
                'class' => 'record-action text-success',
                'action' => 'restore'
            ],
            [
                'label' => ut('modules.trash.actions.force_delete'),
                'url' => fn($item) => 'javascript:void(0);', // 不直接跳转
                'icon' => 'bi bi-x-circle',
                'class' => 'record-action text-danger',
                'action' => 'force_delete'
            ],
        ],
    
        'routeName' => 'trash.index',
        'module' => $module,
        'partialsForfilter' => 'components.filters.filter_fields',
    ])
@endsection
