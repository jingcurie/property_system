@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => __('property.page_title'),
        'pageIcon' => 'bi bi-houses-fill',
        'createUrl' => route('properties.create'),
        'createLabel' => __('property.create_label'),
        'exportUrl' => route('properties.export', request()->all()),
    
        'searchKeywordFields' => [
            __('property.search_fields.property_name'),
            __('property.search_fields.address'),
            __('property.search_fields.city'),
        ],
        'filterFields' => [
            [
                'key' => 'status',
                'label' => __('property.filters.status'),
                'type' => 'select',
                'relation' => 'rentalInfo',
                'column' => 'availability_status',
                'options' => [
                    'Available' => __('property.availability_statuses.Available'),
                    'Leased' => __('property.availability_statuses.Leased'),
                    'Under Maintenance' => __('property.availability_statuses.Under_Maintenance'),
                ],
            ],
            [
                'key' => 'rent',
                'label' => __('property.filters.monthly_rent'),
                'type' => 'range',
                'relation' => 'rentalInfo',
                'column' => 'monthly_rent',
            ],
            [
                'key' => 'city',
                'label' => __('property.filters.city'),
                'type' => 'text',
                'column' => 'address_city',
            ],
            [
                'key' => 'type',
                'label' => __('property.filters.property_type'),
                'type' => 'select',
                'column' => 'property_type',
                'options' => [
                    'apartment' => __('property.property_types.Apartment'),
                    'house' => __('property.property_types.House'),
                    'townhouse' => __('property.property_types.Townhouse'),
                ],
            ],
        ],
    
        'records' => $properties,
        'paginator' => $properties,
    
        'columns' => [
            [
                'label' => __('property.columns.property_name'),
                'field' => 'property_info',
                'type' => 'custom',
                'render' => function ($item) {
                    $name = e($item->property_name ?? '未命名');
                    $cover = $item->media->firstWhere('is_cover', 1);
    
                    $address = implode(
                        ', ',
                        array_filter([
                            $item->address_city,
                            $item->address_province,
                        ]));
    
                    if ($cover) {
                        $url = url('/media/property/' . $item->property_id . '/' . basename($cover->file_path));
    
                        return '<a href="javascript:void(0)" onclick="openMediaModal(' .
                            $item->property_id .
                            ')" class="d-flex align-items-center text-decoration-none gap-3">' .
                            '<img src="' .
                            $url .
                            '" alt="' .
                            $name .
                            '" style="width: 56px; height: 56px; object-fit: cover; object-position: center; border-radius: 15px;">' .
                            '<div class="d-flex flex-column">' .
                            '<span class="text-body fw-medium">' .
                            $name .
                            '</span>' .
                            '<span class="text-muted small">' .
                            $item->address_street .
                            '</span>' .
                            '<span class="text-muted small">' .
                            e($address) .
                            '</span>' .
                            '</div>' .
                            '</a>';
                    } else {
                        return '<span class="text-muted small">无</span>';
                    }
                },
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.address'),
                'fields' => ['address_street', 'address_city'],
                'type' => 'combine',
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.type'),
                'field' => 'property_type',
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.bedrooms_bathrooms'),
                'type' => 'custom',
                'render' => fn($item) => ($item->feature->bedrooms ?? '-') .
                    ' / ' .
                    ($item->feature->bathrooms ?? '-'),
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.rent'),
                'field' => 'rentalInfo.monthly_rent',
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.status'),
                'field' => 'rentalInfo.availability_status',
                'type' => 'badge',
                'badge_map' => [
                    'Available' => 'success',
                    'Leased' => 'secondary',
                    'Under Maintenance' => 'warning',
                ],
                'sortable' => true,
            ],
            [
                'label' => __('property.columns.owner'),
                'field' => 'ownership.owner.full_name',
            ],
        ],
    
        'actions' => [
            [
                'label' => __('property.actions.view'),
                'url' => fn($item) => route('properties.show', $item->property_id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => __('property.actions.edit'),
                'url' => fn($item) => route('properties.edit', $item->property_id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => __('property.actions.delete'),
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
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'properties',
    ])
@endsection
