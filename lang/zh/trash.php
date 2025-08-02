<?php

return [
    'page_title' => '回收站',
    'page_icon' => 'bi bi-trash',
    'bulk_restore' => '批量恢复',
    'bulk_force_delete' => '批量永久删除',

    'filters' => [
        'deleted_by' => '删除人',
        'deleted_at' => '删除时间',
        'module' => '模块',
    ],

    'search_fields' => [
        'name' => '名称',
    ],

    'columns' => [
        'name' => '名称',
        'module' => '所属模块',
        'deleted_by' => '删除人',
        'deleted_at' => '删除时间',
    ],

    'modules' => [
        'properties' => '房源',
        'owners' => '房东',
        'users' => '用户',
        'roles' => '角色',
        'tenants' => '租客',
        'rentalApplications' => '租赁申请',
    ],

    'actions' => [
        'restore' => '恢复',
        'force_delete' => '彻底删除',
        'bulk_restore' => '批量恢复',
        'bulk_force_delete' => '批量删除',
    ],

    'messages' => [
        'confirm_restore' => '确定要恢复该记录吗？',
        'confirm_force_delete' => '确定要彻底删除该记录吗？',
        'confirm_bulk_restore' => '确定要批量恢复选中的记录吗？',
        'confirm_bulk_force_delete' => '确定要批量彻底删除选中的记录吗？',
        'restored_success' => '记录已成功恢复。',
        'force_deleted_success' => '记录已被彻底删除。',
        'bulk_restored_success' => '选中记录已成功恢复。',
        'bulk_force_deleted_success' => '选中记录已被彻底删除。',
        'no_records' => '回收站中没有数据。',
    ],
];
