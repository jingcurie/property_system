@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => __('trash.page_title'),
        'pageIcon' => 'bi bi-trash',
    
        'toolbar' => [
            'default' => [],
            'selected' => [
                [
                    'type' => 'dropdown',
                    'icon' => 'bi bi-list',
                    'label' => __('trash.bulk_action'),
                    'class' => 'btn btn-secondary dropdown-toggle',
                    'items' => [
                        [
                            'label' => __('trash.bulk_restore'),
                            'action' => "bulkRestore",
                            'icon' => 'bi bi-arrow-counterclockwise',
                        ],
                        [
                            'label' => __('trash.bulk_force_delete'),
                            'action' => "bulkForceDelete",
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
                'label' => __('trash.search_fields.name'),
            ],
        ],
    
        'quickFilters' => [
            [
                'key' => 'module',
                'label' => __('trash.filters.module'),
                'type' => 'select',
                'options' => [
                    'properties' => __('trash.modules.properties'),
                    'owners' => __('trash.modules.owners'),
                    'tenants' => __('trash.modules.tenants'),
                    'rentalApplications' => __('trash.modules.rentalApplications'),
                ],
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'deleted_by',
                'label' => __('trash.filters.deleted_by'),
                'type' => 'select',
                'column' => 'deleted_by',
                'options' => $deletedUsers,
            ],
            [
                'key' => 'deleted_at',
                'label' => __('trash.filters.deleted_at'),
                'type' => 'date_range',
                'column' => 'deleted_at',
            ],
        ],
    
        'records' => $records,
        'paginator' => $records,
    
        // 'columns' => [
        //     [
        //         'label' => __('trash.columns.name'),
        //         'column' => match ($module) {
        //             'properties' => 'property_name',
        //             'owners' => 'first_name',
        //             'tenants' => 'first_name',
        //             default => 'name',
        //         },
        //         'sortable' => true,
        //     ],
        //     [
        //         'label' => __('trash.columns.deleted_at'),
        //         'column' => 'deleted_at',
        //         'sortable' => true,
        //     ],
        //     [
        //         'label' => __('trash.columns.deleted_by'),
        //         'type' => 'custom',
        //         'render' => fn($item) => e(optional($item->deletedBy)->name ?? '-'),
        //     ],
        // ],
    
        'actions' => [
            [
                'label' => __('trash.actions.restore'),
                'url' => fn($item) => 'javascript:void(0);', // 不直接跳转
                'icon' => 'bi bi-arrow-counterclockwise',
                'class' => 'text-success',
                'onclick' => fn($item) => "restoreRecord('" .
                    route('trash.restore', [$module, $item->getKey()]) .
                    "', {$item->getKey()})",
            ],
            [
                'label' => __('trash.actions.force_delete'),
                'url' => fn($item) => 'javascript:void(0);', // 不直接跳转
                'icon' => 'bi bi-x-circle',
                'class' => 'text-danger',
                'onclick' => fn($item) => "forceDeleteRecord('" .
                    route('trash.forceDelete', [$module, $item->getKey()]) .
                    "', {$item->getKey()})",
            ],
        ],
    
        // 'batchRestoreUrl' => route('trash.bulk', [$module, 'action' => 'restore']),
        // 'batchForceDeleteUrl' => route('trash.bulk', [$module, 'action' => 'forceDelete']),
        'routeName' => 'trash.index',
        'module' => $module,
        'partialsForfilter' => 'components.filters.filter_fields',
    ])
@endsection
