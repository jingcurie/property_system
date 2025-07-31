<?php
// lang/en/property.php
return [
    // 以下是create表单翻译
    'create_title' => 'Add New Property',
    'edit_title' => 'Edit Property',
    'validation_errors' => 'Form validation errors:',
    
    // 卡片标题
    'basic_info' => 'Basic Information',
    'property_features' => 'Property Features',
    'amenities' => 'Amenities',
    'rental_info' => 'Rental Information',
    'financial_info' => 'Financial Information',
    'compliance_info' => 'Compliance Information',
    'media_upload' => 'Property Images / Video Upload',
    
    // 基础信息字段
    'select_property_type' => 'Select a property type',
    'select_province' => 'Select a province',
    'property_name' => 'Property Name',
    'property_type' => 'Property Type',
    'ownership_type' => 'Ownership Type',
    'year_built' => 'Year Built',
    'street_address' => 'Street Address',
    'city' => 'City',
    'province' => 'Province',
    'postal_code' => 'Postal Code',
    
    // 房屋特征字段
    'bedrooms' => 'Bedrooms',
    'bathrooms' => 'Bathrooms',
    'square_footage' => 'Square Footage',
    'parking_spaces' => 'Parking Spaces',
    'parking_type' => 'Parking Type',
    'heating_type' => 'Heating Type',
    'cooling_type' => 'Cooling Type',
    'laundry' => 'Laundry',
    'furnished' => 'Furnished',
    
    // 配套设施
    'has_gym' => 'Gym',
    'has_pool' => 'Pool',
    'has_balcony' => 'Balcony',
    'has_elevator' => 'Elevator',
    'has_dishwasher' => 'Dishwasher',
    'has_fridge' => 'Fridge',
    'has_stove' => 'Stove',
    'has_microwave' => 'Microwave',
    'has_air_conditioning' => 'Air Conditioning',
    
    // 出租信息字段
    'availability_status' => 'Availability Status',
    'monthly_rent' => 'Monthly Rent ($)',
    'security_deposit' => 'Security Deposit ($)',
    'lease_term_type' => 'Lease Term Type',
    'min_lease_term' => 'Min Lease Term (Months)',
    'available_date' => 'Available Date',
    'utilities_included' => 'Utilities Included',
    'pet_policy' => 'Pet Policy',
    'pet_fee' => 'Pet Fee ($)',
    
    // 财务信息字段
    'management_fee_percentage' => 'Management Fee (%)',
    'annual_property_tax' => 'Annual Property Tax ($)',
    'maintenance_fund' => 'Maintenance Fund ($)',
    'hst_included' => 'HST Included in Rent',
    
    // 合规信息字段
    'property_tax_id' => 'Property Tax ID',
    'rental_license_number' => 'Rental License Number',
    'insurance_policy_number' => 'Insurance Policy Number',
    'fire_safety_compliance' => 'Fire Safety Compliance',
    'accessibility_compliance' => 'Accessibility Compliance',
    'last_inspection_date' => 'Last Inspection Date',
    
    // 按钮和操作
    'save_property' => 'Save Property',
    'update_property' => 'Update Property',
    'cancel' => 'Cancel',
    'set_as_cover' => 'Set as Cover',
    
    // 提示信息
    'upload_hint' => 'Click to upload or drag files, maximum 20 files. Click "Set as Cover" to select cover image.',
    'required_field' => 'Required field',
    
    // 选项值
    'property_types' => [
        'Apartment' => 'Apartment',
        'House' => 'House',
        'Townhouse' => 'Townhouse',
        'Condo' => 'Condo',
        'Basement' => 'Basement',
        'Other' => 'Other'
    ],
    
    'ownership_types' => [
        'Owned' => 'Owned',
        'Managed' => 'Managed'
    ],
    
    'parking_types' => [
        'Indoor' => 'Indoor',
        'Outdoor' => 'Outdoor',
        'Garage' => 'Garage',
        'None' => 'None'
    ],
    
    'laundry_options' => [
        'In-unit' => 'In-unit',
        'Shared' => 'Shared',
        'None' => 'None'
    ],
    
    'availability_statuses' => [
        'Available' => 'Available',
        'Leased' => 'Leased',
        'Under_Maintenance' => 'Under Maintenance'
    ],
    
    'lease_term_types' => [
        'Monthly' => 'Monthly',
        'Fixed_Term' => 'Fixed Term',
        'Annual' => 'Annual'
    ],
    
    'pet_policies' => [
        'Allowed' => 'Allowed',
        'Restricted' => 'Restricted',
        'Not_Allowed' => 'Not Allowed'
    ],
    
    'utilities' => [
        'Water' => 'Water',
        'Electricity' => 'Electricity',
        'Gas' => 'Gas',
        'Internet' => 'Internet',
        'Cable' => 'Cable'
    ],

    'create_title' => 'Add New Property',
    'edit_title' => 'Edit Property', 
    'validation_errors' => 'Form validation errors:',

    // 以下内容添加到 lang/en/property.php 文件中：

    // 页面相关
    'page_title' => 'Properties',
    'create_label' => 'Property',
    'export_label' => 'Export',

    // 搜索字段
    'search_fields' => [
        'property_name' => 'Property Name',
        'address' => 'Address',
        'city' => 'City',
    ],

    // 筛选字段
    'filters' => [
        'status' => 'Status',
        'monthly_rent' => 'Monthly Rent',
        'city' => 'City',
        'property_type' => 'Property Type',
    ],

    // 列表列标题
    'columns' => [
        'property_name' => 'Property Name',
        'cover' => 'Cover',
        'address' => 'Address',
        'type' => 'Type',
        'bedrooms_bathrooms' => 'Bedrooms/Bathrooms',
        'rent' => 'Rent',
        'status' => 'Status',
        'owner' => 'Owner',
    ],

    // 操作按钮
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],
];