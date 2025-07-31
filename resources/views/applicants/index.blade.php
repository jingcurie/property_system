@extends('layouts.app')

@section('content')
    @component('components.pages.index-table', [
        'pageTitle' => '申请人管理',
        'pageIcon' => 'bi bi-person-lines-fill',
        'createUrl' => route('applicants.create'),
        'createLabel' => '申请人',
        'exportUrl' => '',
        'records' => $applicants,
        'searchKeywordFields' => ['full_name', 'email', 'phone', 'address_line1'],
        'paginator' => $applicants,
        'batchDeleteUrl' => '',
        'routeName' => 'applicants.index',
        'partialsForfilter' => 'components.filters.filter_fields',
        'module' => 'applicants',

        'toolbar' => [
            'default' => [
                [
                    'type' => 'link',
                    'icon' => 'bi bi-plus-circle',
                    'label' => __('property.create_label'),
                    'url' => route('applicants.create'),
                    'class' => 'btn btn-primary',
                ],
                [
                    'type' => 'link',
                    'icon' => 'bi bi-download',
                    'label' => __('property.export_label'),
                    'url' => "",
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

        'filterFields' => [
            [
                'key' => 'phone',
                'label' => '电话',
                'type' => 'text',
                'column' => 'phone',
            ],
        ],

        'columns' => [
           [
                'label' => '姓名 / 年龄',
                'field' => 'full_name',
                'type' => 'custom',
                'render' => fn($item) => "<div><div class='fw-bold'>" . e($item->full_name) . "</div><div class='text-muted small'>" . ($item->date_of_birth && $item->date_of_birth <= now() ? floor(\Carbon\Carbon::parse($item->date_of_birth)->diffInYears(now())) . ' 岁' : '-') . "</div></div>",
            ],
           [
    'label' => '地址',
    'type' => 'custom',
    'render' => fn($item) => "<div><div class='fw-semibold'>" . e($item->address_line1 ?? '-') . "</div><div class='text-muted small'>" . e($item->city . ' / ' . $item->state) . "</div></div>",
],
            [
                'label' => '联系方式',
                'type' => 'custom',
                'render' => fn($item) => "<div><div><i class='bi bi-envelope me-1'></i>" . e($item->email) . "</div><div><i class='bi bi-telephone me-1'></i>" . e($item->phone) . "</div></div>",
            ],
            [
                'label' => '月收入 / 单位',
                'type' => 'custom',
                'render' => fn($item) => "<div><div>$" . number_format((float)(optional($item->employmentDetail)->monthly_income ?? 0)) . "</div><div class='text-muted small'>" . e(optional($item->employmentDetail)->employer_name ?? '-') . "</div></div>",
            ],
            [
                'label' => '保险',
                'type' => 'custom',
                'render' => fn($item) => $item->renters_insurance_provider
                    ? '<i class="bi bi-shield-check text-success" title="有保险"></i>'
                    : '<i class="bi bi-shield-x text-muted" title="无保险"></i>',
            ],
            [
                'label' => '创建时间',
                'field' => 'created_at',
                'sortable' => true,
                'type' => 'custom',
                'render' => fn($item) => "<div>" . e($item->created_at->format('Y-m-d H:i:s')) . "</div><div class='text-muted small'>" . e($item->created_at->diffForHumans()) . "</div>",
            ],
        ],

        'actions' => [
            [
                'label' => '查看',
                'url' => fn($item) => route('applicants.show', $item->id),
            ],
            [
                'label' => '编辑',
                'url' => fn($item) => route('applicants.edit', $item->id),
            ],
            [
                'label' => '删除',
                'url' => fn($item) => route('applicants.destroy', $item->id),
                'method' => 'delete',
            ],
        ],
    ])
    @endcomponent
@endsection
