@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => __('owner.page_title'),
        'pageIcon' => 'bi bi-person-badge',
        'createUrl' => route('owners.create'),
        'createLabel' => __('owner.create_label'),
        'exportUrl' => route('owners.export', request()->all()),
        'rowClickUrl' => fn($item) => route('owners.show', $item->property_id),
    
        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'property_name',
                'label' => __('property.search_fields.property_name'),
            ],
            [
                'relation' => null,
                'column' => 'email',
                'label' => __('owner.search_fields.email'),
            ],
            [
                'relation' => null,
                'column' => 'phone',
                'label' => __('owner.search_fields.phone'),
            ],
        ],
    
        'filterFields' => [
            [
                'key' => 'address',
                'label' => __('owner.address'),
                'column' => 'address',
            ],
        ],

        'records' => $owners,
        'paginator' => $owners,
    
        'columns' => [
           
        ],
    
        'actions' => [
            [
                'label' => __('owner.actions.view'),
                'url' => fn($item) => route('properties.show', $item->owner_id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => __('owner.actions.edit'),
                'url' => fn($item) => route('properties.edit', $item->owner_id),
                'icon' => 'bi bi-pencil-square',
            ],
            [
                'label' => __('owner.actions.delete'),
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" .
                    route('owners.destroy', $item->owner_id) .
                    "')",
            ],
        ],
    
        'batchDeleteUrl' => route('owners.batchDelete'),
        'routeName' => 'owners.index',
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'owners',
    ])
@endsection
