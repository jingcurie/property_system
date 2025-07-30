@extends('layouts.app')

@section('content')
    @include('components.pages.index-table', [
        'pageTitle' => 'File Management',
        'pageIcon' => 'bi bi-folder2-open',
        'createUrl' => null, // 不需要新增按钮可设为 null
        'createLabel' => '',
        'exportUrl' => null,

        'searchKeywordFields' => [
            [
                'relation' => null,
                'column' => 'filename',
                'label' => 'File name',
            ],
        ],

        'filterFields' => [
            ['key' => 'category', 'label' => 'Category'],
            ['key' => 'uploaded_by', 'label' => 'Uploaded By'],
        ],

        'filterFields' => [
            [
                'key' => 'category',
                'label' => 'Category',
                'type' => 'select',
                'column' => 'category',
                'options' => [
                    'uncategorized' => 'Uncategorized',
                    'applicant' => 'Applicant',
                    'lease' => 'Lease',
                    'report' => 'Report',
                ],
            ],
            [
                'key' => 'rent',
                'label' => 'Uploaded by',
                'type' => 'text',
                'column' => 'uploaded_by',
            ],
        ],
    
        'records' => $files,
        'paginator' => $files,
    
        'columns' => [
            [
                'label' => 'File',
                'type' => 'custom',
                'render' => fn($item) => view('files.partials.file_icon', ['file' => $item])->render(),
            ],
            [
                'label' => 'Size',
                'column' => 'size',
                'sortable' => true,
                'type' => 'custom',
                'render' => fn($file) => formatBytes($file->size),
            ],
    
            [
                'label' => 'Category',
                'column' => 'category',
                'sortable' => true,
            ],
            [
                'label' => 'Uploaded By',
                'column' => 'uploaded_by_user.name',
                'sortable' => true,
            ],
            [
                'label' => 'Updated At',
                'column' => 'updated_at',
                'sortable' => true,
            ],
        ],
    
        'actions' => [
            [
                'label' => 'Email',
                'url' => fn($item) => route('files.email', $item->id),
                'icon' => 'bi bi-envelope',
            ],
            [
                'label' => 'Download',
                'url' => fn($item) => route('files.download', $item->id),
                'icon' => 'bi bi-download',
            ],
            [
                'label' => 'View',
                'url' => fn($item) => route('files.preview', $item->id),
                'icon' => 'bi bi-eye',
            ],
            [
                'label' => 'Delete',
                'url' => fn($item) => 'javascript:void(0);',
                'icon' => 'bi bi-trash',
                'class' => 'text-danger',
                'onclick' => fn($item) => "submitDelete('" . route('files.destroy', $item->id) . "')",
            ],
        ],
    
        //'batchDeleteUrl' => route('files.batchDelete'),
        'partialsForfilter' => 'components.filters.filter_fields',
        'routeName' => 'file-center.index',
        'module' => 'files',
    ])
@endsection
