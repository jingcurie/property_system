<?php

return [
    // show 
    'page_title' => 'Lease Details',
    'sections' => [
        'basic_info'    => 'Basic Information',
        'tenant_info'   => 'Tenant Information',
        'property_info' => 'Property Information',
    ],
    'fields' => [
        'lease_number'  => 'Lease Number',
        'start_date'    => 'Start Date',
        'end_date'      => 'End Date',
        'status'        => 'Status',
        'tenant'        => 'Tenant',
        'phone'         => 'Phone',
        'email'         => 'Email',
        'property'      => 'Property',
        'address'       => 'Address',
        'monthly_rent'  => 'Monthly Rent',
    ],

    // index
    'page_title'    => 'Lease Management',
    'create_label'  => 'Add Lease',
    'search_fields' => [
        'lease_number' => 'Lease Number',
        'tenant'       => 'Tenant Name',
        'address'      => 'Property Address',
    ],
    'filters' => [
        'status'       => 'Lease Status',
        'start_date'   => 'Start Date',
        'monthly_rent' => 'Rent',
    ],
    'columns' => [
        'lease_number'     => 'Number',
        'tenant'           => 'Tenant',
        'property_address' => 'Address',
        'rent'             => 'Rent',
        'dates'            => 'Lease Term',
        'status'           => 'Status',
    ],
    'statuses' => [
        'draft'      => 'Draft',
        'active'     => 'Active',
        'terminated' => 'Terminated',
    ],
    'actions' => [
        'view'         => 'View',
        'edit'         => 'Edit',
        'delete'       => 'Delete',
        'download_pdf' => 'Download PDF',
        'generate_pdf' => 'Generate Lease PDF',
    ],

];
