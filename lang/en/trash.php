<?php

return [
    'page_title' => 'Recycle Bin',
    'page_icon' => 'bi bi-trash',
    'bulk_restore' => 'bulk retore',
    'bulk_force_delete' => 'bulk force delete',

    'filters' => [
        'deleted_by' => 'Deleted By',
        'deleted_at' => 'Deleted Date',
        'module' => 'Module',
    ],

    'search_fields' => [
        'name' => 'Name',
    ],

    'columns' => [
        'name' => 'Name',
        'module' => 'Module',
        'deleted_by' => 'Deleted By',
        'deleted_at' => 'Deleted At',
    ],

    'modules' => [
        'properties' => 'Properties',
        'owners' => 'Owners',
        'users' => 'Users',
        'roles' => 'Roles',
        'tenants' => 'Tenants',
        'rentalApplications' => 'Rental Applications',
    ],

    'actions' => [
        'restore' => 'Restore',
        'force_delete' => 'Permanently Delete',
        'bulk_restore' => 'Bulk Restore',
        'bulk_force_delete' => 'Bulk Delete',
    ],

    'messages' => [
        'confirm_restore' => 'Are you sure you want to restore this record?',
        'confirm_force_delete' => 'Are you sure you want to permanently delete this record?',
        'confirm_bulk_restore' => 'Are you sure you want to restore selected records?',
        'confirm_bulk_force_delete' => 'Are you sure you want to permanently delete selected records?',
        'restored_success' => 'Record restored successfully.',
        'force_deleted_success' => 'Record permanently deleted successfully.',
        'bulk_restored_success' => 'Selected records restored successfully.',
        'bulk_force_deleted_success' => 'Selected records permanently deleted successfully.',
        'no_records' => 'No records found in recycle bin.',
    ],
];
