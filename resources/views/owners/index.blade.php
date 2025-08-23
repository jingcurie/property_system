@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => ut('modules.owner.page_title'),
        'pageIcon' => 'bi bi-person-badge',
        // 'createUrl' => route('owners.create'),
        // 'createLabel' => __('owner.create_label'),
        // 'exportUrl' => route('owners.export', request()->all()),
        'rowClickUrl' => fn($item) => route('owners.show', $item->owner_id),
    
        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => ut('modules.owner.create_label'),
                    'url' => route('owners.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => ut('modules.owner.export_label'),
                    'url' => '',
                    route('leases.export', request()->all()),
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
                            'action' => 'delete',
                            'icon' => 'bi bi-trash',
                        ],
                    ],
                ],
            ],
        ],
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'property_name',
                'label' => ut('modules.property.search_property_name'),
            ],
            [
                'relation' => null,
                'column' => 'email',
                'label' => ut('modules.owner.search_fields.email'),
            ],
            [
                'relation' => null,
                'column' => 'phone',
                'label' => ut('modules.owner.search_fields.phone'),
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'address',
                'label' => ut('modules.owner.address'),
                'column' => 'address',
            ],
        ],
    
        'records' => $owners,
        'paginator' => $owners,
    
        'columns' => [
            [
                'label' => ut('modules.owner.columns.owner_name'),
                'type' => 'custom',
                'render' => function ($item) {
                    $fullName = trim(($item->first_name ?? '') . ' ' . ($item->last_name ?? '')) ?: '未命名';
                    $email = e($item->email ?? '-');
                    $phone = e($item->phone ?? '-');
    
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<span class="text-body fw-medium">' . e($fullName) . '</span>';
                    $html .= '<span class="text-muted small">' . $email . '</span>';
                    $html .= '<span class="text-muted small">' . $phone . '</span>';
                    $html .= '</div>';
    
                    return $html;
                },
                'sortable' => true,
            ],
            [
                'label' => ut('modules.owner.columns.address'),
                'column' => 'address',
                'sortable' => true,
            ],
            [
                'label' => ut('modules.owner.columns.emergency_contact'),
                'type' => 'custom',
                'render' => fn($item) => e(
                    ($item->emergency_contact ?? '-') . ' / ' . ($item->emergency_contact_phone ?? '-')),
                'sortable' => false,
            ],
            [
                'label' => ut('modules.owner.columns.tax_id'),
                'column' => 'tax_id',
                'sortable' => true,
            ],
            [
                'label' => ut('modules.owner.columns.is_active'),
                'column' => 'is_active',
                'type' => 'badge',
                'badge_map' => [
                    1 => 'success',
                    0 => 'secondary',
                ],
                'render' => fn($item) => $item->is_active
                                ? ut('common.active')
            : ut('common.inactive'),
                'sortable' => true,
            ],
        ],
    
        'actions' => [
            [
                'label' => ut('modules.owner.actions.view'),
                'url' => fn($item) => route('properties.show', $item->owner_id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => ut('modules.owner.actions.edit'),
                'url' => fn($item) => route('properties.edit', $item->owner_id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => ut('modules.owner.actions.delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'record-action text-danger',
            ],
        ],
    
        // 'batchDeleteUrl' => route('owners.batchDelete'),
        'routeName' => 'owners.index',
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'owners',
    ])
@endsection
