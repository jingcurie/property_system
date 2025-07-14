@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => '房源管理',
        'pageIcon' => 'bi bi-houses-fill',
        'createUrl' => route('properties.create'),
        'createLabel' => '房源',
        'exportUrl' => route('properties.export', request()->all()),
    
        'searchKeywordFields' => ['房源名称', '地址', '城市'],
        'filterFields' => [
            ['key' => 'status', 'label' => '状态'],
            ['key' => 'rent', 'label' => '租金'],
            ['key' => 'city', 'label' => '城市'],
            ['key' => 'type', 'label' => '房源类型'],
            ['key' => 'owner_id', 'label' => '房东'],
        ],
    
        'records' => $properties,
        'paginator' => $properties,
    
        'columns' => [
            [
                'label' => '房源名称',
                'field' => 'property_name',
                'link' => function ($item) {
                    return route('properties.show', $item->property_id);
                },
                'sortable' => true
            ],
            [
                'label' => '封面',
                'field' => 'file_path',
                'type' => 'image',
                'style' => 'object-fit: cover; object-position: center;',
            ],
            ['label' => '地址', 'fields' => ['address_street', 'address_city'], 'type' => 'combine', 'sortable' => true],
            ['label' => '类型', 'field' => 'property_type', 'sortable' => true],
            [
                'label' => '卧室/卫浴',
                'type' => 'custom',
                'render' => fn($item) => $item->feature->bedrooms . ' / ' . $item->feature->bathrooms,
                'sortable' => true
            ],
            ['label' => '租金', 'field' => 'rentalInfo.monthly_rent', 'sortable' => true],
            [
                'label' => '状态',
                'field' => 'rentalInfo.availability_status',
                'type' => 'badge',
                'badge_map' => [
                    'Available' => 'success',
                    'Leased' => 'secondary',
                    'Under Maintenance' => 'warning',
                ],
                'sortable' => true
            ],
            ['label' => '房东', 'field' => 'ownership.owner.full_name'],
        ],
    
        'actions' => [
            [
                'label' => '查看',
                'url' => fn($item) => route('properties.show', $item->property_id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => '编辑',
                'url' => fn($item) => route('properties.edit', $item->property_id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => '删除',
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" .
                    route('properties.destroy', $item->property_id) .
                    "')",
            ],
        ],
    
        'batchDeleteUrl' => route('properties.batchDelete'),
        'routeName' => 'properties.index',
        'partialsForfilter' => 'properties.partials.filter_fields',
        'module' => 'properties',
    ])
@endsection
