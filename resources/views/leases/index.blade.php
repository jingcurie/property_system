@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => ut('modules.lease.page_title'),
        'pageIcon' => 'bi bi-file-earmark-text',
        'rowClickUrl' => fn($item) => route('leases.show', $item->lease_id),

        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => ut('modules.lease.create_label'),
                    'url' => route('leases.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => ut('modules.lease.export_label'),
                    'url' => route('leases.export', request()->all()),
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
                        [
                            'label' => '批量审核通过',
                            'action' => 'bulk-approve',
                            'icon' => 'bi bi-check-lg',
                        ],
                        [
                            'label' => '批量拒绝',
                            'action' => 'bulk-reject',
                            'icon' => 'bi bi-x-lg',
                        ],
                    ],
                ],
            ],
        ],
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'lease_number',
                'label' => ut('modules.lease.search_fields.lease_number'),
            ],
            [
                'relation' => 'tenants',
                'column' => 'first_name',
                'label' => ut('modules.lease.search_fields.tenant'),
            ],
            [
                'relation' => 'tenants',
                'column' => 'last_name',
                'label' => '',
            ],
            [
                'relation' => 'property',
                'column' => 'address_street',
                'label' => ut('modules.lease.search_fields.address'),
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'status',
                'label' => ut('modules.lease.filters.status'),
                'type' => 'select',
                'column' => 'status',
                'options' => [
                    'draft' => ut('modules.lease.statuses.draft'),
                    'active' => ut('modules.lease.statuses.active'),
                    'terminated' => ut('modules.lease.statuses.terminated'),
                ],
            ],
            [
                'key' => 'start_date',
                'label' => ut('modules.lease.filters.start_date'),
                'type' => 'date',
                'column' => 'start_date',
            ],
            [
                'key' => 'rent',
                'label' => ut('modules.lease.filters.monthly_rent'),
                'type' => 'range',
                'column' => 'monthly_rent',
            ],
        ],
    
        'records' => $leases,
        'paginator' => $leases,
    
        'columns' => [
            [
                'label' => ut('modules.lease.columns.lease_number'),
                'column' => 'lease_number',
                'sortable' => true,
            ],
            [
                'label' => ut('modules.lease.columns.primary_tenant'),
                'field' => 'primary_tenant',
                'type' => 'custom',
                'render' => function ($item) {
                    $primary = $item->tenants->first();
                    return $primary ? $primary->first_name . ' ' . $primary->last_name : '-';
                },
                'sortable' => false,
            ],
            [
                'label' => ut('modules.lease.columns.property_address'),
                'column' => 'property.address_street',
                'type' => 'combine',
                'columns' => ['property.address_street', 'property.address_city'],
                'sortable' => true,
            ],
            [
                'label' => ut('modules.lease.columns.rent'),
                'column' => 'monthly_rent',
                'sortable' => true,
            ],
            [
                'label' => ut('modules.lease.columns.dates'),
                'type' => 'custom',
                'sortable' => true,
                'sort_field' => 'start_date',
                'render' => fn($item) => e($item->start_date) . ' ~ ' . e($item->end_date),
            ],
            [
                'label' => ut('modules.lease.columns.status'),
                'column' => 'status',
                'type' => 'badge',
                'badge_map' => [
                    'draft' => 'secondary',
                    'active' => 'success',
                    'terminated' => 'danger',
                ],
                'sortable' => true,
            ],
        ],
    
        'actions' => [
            [
                'label' => ut('modules.lease.actions.view'),
                'url' => fn($item) => route('leases.show', $item->lease_id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => ut('modules.lease.actions.edit'),
                'url' => fn($item) => route('leases.edit', $item->lease_id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => ut('modules.lease.actions.download_pdf'),
                'url' => fn($item) => route('leases.exportPdf', $item->lease_id),
                'icon' => 'bi bi-file-earmark-pdf',
                'class' => 'text-danger',
            ],
            [
                'label' => ut('modules.lease.actions.delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" . route('leases.destroy', $item->lease_id) . "')",
            ],
        ],
    
        'batchDeleteUrl' => route('leases.batchDelete'),
        'routeName' => 'leases.index',
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'leases',
    ])
@endsection
