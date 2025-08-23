<?php

return [
    //show 
    'page_title' => '租赁详情',
    'sections' => [
        'basic_info' => '基本信息',
        'tenant_info' => '租客信息',
        'property_info' => '房源信息',
    ],
    'fields' => [
        'lease_number' => '租赁编号',
        'start_date' => '开始日期',
        'end_date' => '结束日期',
        'status' => '状态',
        'tenant' => '租客',
        'phone' => '电话',
        'email' => '邮箱',
        'property' => '房源',
        'address' => '地址',
        'monthly_rent' => '月租金',
    ],

    //index
    'page_title' => '租约管理',
    'create_label' => '新增租约',
    'search_fields' => [
        'lease_number' => '租约编号',
        'tenant' => '租户姓名',
        'address' => '房源地址',
    ],
    'filters' => [
        'status' => '租约状态',
        'start_date' => '开始时间',
        'monthly_rent' => '租金',
    ],
    'columns' => [
        'lease_number' => '编号',
        'tenant' => '租户',
        'property_address' => '地址',
        'rent' => '租金',
        'dates' => '租期',
        'status' => '状态',
    ],
    'statuses' => [
        'draft' => '草稿',
        'active' => '进行中',
        'terminated' => '已终止',
    ],
    'actions' => [
        'view' => '查看',
        'edit' => '编辑',
        'delete' => '删除',
        'download_pdf' => '下载 PDF',
        'generate_pdf' => '生成合同PDF',
    ],

];
