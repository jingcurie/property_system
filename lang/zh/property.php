<?php
// lang/zh/property.php
return [
    // 页面标题
    'create_title' => '新增房源',
    'edit_title' => '编辑房源',
    'validation_errors' => '表单验证错误：',
    
    // 卡片标题
    'basic_info' => '基础信息',
    'property_features' => '房屋特征',
    'amenities' => '配套设施',
    'rental_info' => '出租信息',
    'financial_info' => '财务信息',
    'compliance_info' => '合规信息',
    'media_upload' => '房源图片 / 视频上传',
    
    // 基础信息字段
    'select_property_type' => '请选择房产类型',
    'select_province' => '选择省份',
    'property_name' => '房源名称',
    'property_type' => '房源类型',
    'ownership_type' => '业权类型',
    'year_built' => '建造年份',
    'street_address' => '街道地址',
    'city' => '城市',
    'province' => '所在省份',
    'postal_code' => '邮编',
    
    // 房屋特征字段
    'bedrooms' => '卧室数',
    'bathrooms' => '卫生间数',
    'square_footage' => '面积（平方英尺）',
    'parking_spaces' => '停车位数量',
    'parking_type' => '停车类型',
    'heating_type' => '供暖类型',
    'cooling_type' => '制冷类型',
    'laundry' => '洗衣方式',
    'furnished' => '带家具',
    
    // 配套设施
    'has_gym' => '健身房',
    'has_pool' => '游泳池',
    'has_balcony' => '阳台',
    'has_elevator' => '电梯',
    'has_dishwasher' => '洗碗机',
    'has_fridge' => '冰箱',
    'has_stove' => '炉灶',
    'has_microwave' => '微波炉',
    'has_air_conditioning' => '空调',
    
    // 出租信息字段
    'availability_status' => '出租状态',
    'monthly_rent' => '月租金 ($)',
    'security_deposit' => '押金 ($)',
    'lease_term_type' => '租期类型',
    'min_lease_term' => '最短租期（月）',
    'available_date' => '可入住日期',
    'utilities_included' => '包含水电费项目',
    'pet_policy' => '宠物政策',
    'pet_fee' => '宠物附加费 ($)',
    
    // 财务信息字段
    'management_fee_percentage' => '管理费比例 (%)',
    'annual_property_tax' => '年物业税 ($)',
    'maintenance_fund' => '维修基金 ($)',
    'hst_included' => '租金已含 HST（销售税）',
    
    // 合规信息字段
    'property_tax_id' => '物业税号',
    'rental_license_number' => '租赁许可证编号',
    'insurance_policy_number' => '保险单号',
    'fire_safety_compliance' => '通过消防合规检查',
    'accessibility_compliance' => '符合无障碍标准',
    'last_inspection_date' => '最近检查日期',
    
    // 按钮和操作
    'save_property' => '保存房源',
    'update_property' => '更新房源',
    'cancel' => '取消',
    'set_as_cover' => '设为封面',
    
    // 提示信息
    'upload_hint' => '点击上传或拖拽文件，最多上传 20 个。点击"设为封面"按钮选择封面图。',
    'required_field' => '必填字段',
    
    // 选项值
    'property_types' => [
        'Apartment' => '公寓',
        'House' => '独立屋',
        'Townhouse' => '联排别墅',
        'Condo' => '共管公寓',
        'Basement' => '地下室',
        'Other' => '其他'
    ],
    
    'ownership_types' => [
        'Owned' => '自有',
        'Managed' => '代管'
    ],
    
    'parking_types' => [
        'Indoor' => '室内',
        'Outdoor' => '室外',
        'Garage' => '车库',
        'None' => '无'
    ],
    
    'laundry_options' => [
        'In-unit' => '房内洗衣',
        'Shared' => '共用洗衣',
        'None' => '无'
    ],
    
    'availability_statuses' => [
        'Available' => '可出租',
        'Leased' => '已出租',
        'Under_Maintenance' => '维护中'
    ],
    
    'lease_term_types' => [
        'Monthly' => '月租',
        'Fixed_Term' => '固定期限',
        'Annual' => '年租'
    ],
    
    'pet_policies' => [
        'Allowed' => '允许',
        'Restricted' => '限制',
        'Not_Allowed' => '不允许'
    ],
    
    'utilities' => [
        'Water' => '水费',
        'Electricity' => '电费',
        'Gas' => '燃气费',
        'Internet' => '网费',
        'Cable' => '有线电视费'
    ],

    'create_title' => '新增房源',
    'edit_title' => '编辑房源',
    'validation_errors' => '表单验证错误：',

    // index 翻译

    // 页面相关
    'page_title' => '房源管理',
    'create_label' => '房源',
    'export_label' => '导出',

    // 搜索字段
    'search_fields' => [
        'property_name' => '房源名称',
        'address' => '地址',
        'city' => '城市',
    ],

    // 筛选字段
    'filters' => [
        'status' => '状态',
        'monthly_rent' => '月租金',
        'city' => '城市',
        'property_type' => '房源类型',
    ],

    // 列表列标题
    'columns' => [
        'property_name' => '房源',
        'cover' => '封面',
        'address' => '地址',
        'type' => '类型',
        'bedrooms_bathrooms' => '卧室/卫浴',
        'rent' => '租金',
        'status' => '状态',
        'owner' => '房东',
    ],

    // 操作按钮
    'actions' => [
        'view' => '查看',
        'edit' => '编辑',
        'delete' => '删除',
    ],

    
];