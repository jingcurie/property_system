@extends('layouts.app')

@section('content')
@include('components.pages.index-table', [
    'pageTitle' => '租赁申请列表',
    'pageIcon' => 'bi bi-clipboard-check',
    'createUrl' => route('rental_applications.create'),
    'createLabel' => '申请',
    'exportUrl' => '',

    'searchKeywordFields' => ['编号', '备注'],
    'filterFields' => [
        ['key' => 'status', 'label' => '状态'],
        ['key' => 'reviewed_by', 'label' => '审核人'],
    ],

    'records' => $applications,
    'paginator' => $applications,

    'columns' => [
        ['label' => '编号', 'field' => 'application_code', 'sortable' => true],
        [
            'label' => '状态',
            'type' => 'custom',
            'render' => function ($item) {
                $map = [
                    'submitted' => 'secondary',
                    'under_review' => 'info',
                    'approved' => 'success',
                    'rejected' => 'danger',
                ];
                $class = $map[$item->status] ?? 'secondary';
                return "<span class='badge bg-$class'>{$item->status}</span>";
            },
        ],
        ['label' => '申请时间', 'field' => 'submitted_at', 'sortable' => true],
        ['label' => '审核人', 'field' => 'reviewed_by', 'sortable' => false],
    ],

    'actions' => [
        [
            'label' => '查看',
            'url' => fn($item) => route('rental_applications.show', $item->id),
            'icon' => 'bi bi-eye',
        ],
        [
            'label' => '编辑',
            'url' => fn($item) => route('rental_applications.edit', $item->id),
            'icon' => 'bi bi-pencil',
        ],
        [
            'label' => '删除',
            'url' => fn($item) => 'javascript:void(0);',
            'icon' => 'bi bi-trash',
            'class' => 'text-danger',
            'onclick' => fn($item) => "submitDelete('" . route('rental_applications.destroy', $item->id) . "')",
        ],
    ],

    'batchDeleteUrl' => route('rental_applications.batchDelete', [], false),
    'routeName' => 'rental_applications.index',
    'partialsForfilter' => 'rental_applications.partials.filter_fields',
    'module' => 'rental_applications'
])
@endsection
