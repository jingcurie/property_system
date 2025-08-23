<?php
/**
 * 中文统一翻译文件
 * 包含所有模块的中文翻译
 */
return [
    // ===== 通用翻译 =====
    'common' => [
        // 基础操作
        'create' => '新增',
        'edit' => '编辑',
        'view' => '查看',
        'delete' => '删除',
        'save' => '保存',
        'cancel' => '取消',
        'submit' => '提交',
        'update' => '更新',
        'refresh' => '刷新',
        'export' => '导出',
        'import' => '导入',
        'search' => '搜索',
        'filter' => '筛选',
        'clear' => '清空',
        'reset' => '重置',
        'back' => '返回',
        'close' => '关闭',
        'confirm' => '确认',
        'approve' => '审核通过',
        'reject' => '拒绝',
        'review' => '审核',
        
        // 状态
        'status' => '状态',
        'active' => '激活',
        'inactive' => '未激活',
        'enabled' => '启用',
        'disabled' => '禁用',
        'pending' => '待处理',
        'completed' => '已完成',
        'cancelled' => '已取消',
        
        // 时间
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
        'submitted_at' => '提交时间',
        'reviewed_at' => '审核时间',
        
        // 分页
        'showing' => '显示',
        'to' => '到',
        'of' => '共',
        'entries' => '条记录',
        'per_page' => '每页显示',
        
        // 消息
        'success' => '成功',
        'error' => '错误',
        'warning' => '警告',
        'info' => '信息',
        'loading' => '加载中...',
        'no_data' => '暂无数据',
        'no_results' => '暂无结果',
        
        // 文件操作
        'upload' => '上传',
        'download' => '下载',
        'file_management' => '文件管理',
        'media_management' => '媒体管理',
        
        // 批量操作
        'batch_operations' => '批量操作',
        'batch_delete' => '批量删除',
        'batch_approve' => '批量审核通过',
        'batch_reject' => '批量拒绝',
        'batch_export' => '批量导出',
        
        // 导出格式
        'export_excel' => '导出Excel',
        'export_pdf' => '导出PDF',
        'export_csv' => '导出CSV',
    ],
    
    // ===== 模块翻译 =====
    'modules' => [
        // 房源模块
        'property' => [
            'module_name' => '房源管理',
            'page_title' => '房源列表',
            'create_title' => '新增房源',
            'edit_title' => '编辑房源',
            'show_title' => '房源详情',
            
            // 基础信息
            'property_name' => '房源名称',
            'property_type' => '房源类型',
            'ownership_type' => '业权类型',
            'year_built' => '建造年份',
            'street_address' => '街道地址',
            'city' => '城市',
            'province' => '所在省份',
            'postal_code' => '邮编',
            
            // 房屋特征
            'bedrooms' => '卧室数',
            'bathrooms' => '卫生间数',
            'square_footage' => '面积（平方英尺）',
            'parking_spaces' => '停车位数量',
            'parking_type' => '停车类型',
            
            // 出租信息
            'availability_status' => '出租状态',
            'monthly_rent' => '月租金',
            'security_deposit' => '押金',
            'lease_term_type' => '租期类型',
            'available_date' => '可入住日期',
            
            // 搜索字段
            'search_property_name' => '房源名称',
            'search_address' => '地址',
            'search_city' => '城市',
            
            // 筛选字段
            'filter_monthly_rent' => '月租金',
            'filter_city' => '城市',
            'filter_property_type' => '房源类型',
            'filter_bedrooms' => '卧室数',
            'filter_square_footage' => '面积',
            'filter_parking_spaces' => '停车位',
            
            // 列标题
            'column_property_name' => '房源名称',
            'column_type' => '类型',
            'column_bedrooms_bathrooms' => '卧室/卫生间',
            'column_rent' => '租金',
            'column_status' => '状态',
            'column_owners' => '业主',
                            'column_features' => '设施',
                
                // 表单字段
                'basic_info' => '基本信息',
                'property_name' => '房源名称',
                'property_type' => '房源类型',
                'select_property_type' => '请选择房源类型',
                'ownership_type' => '业权类型',
                'year_built' => '建造年份',
                'street_address' => '街道地址',
                'city' => '城市',
                'province' => '所在省份',
                'select_province' => '请选择省份',
                'postal_code' => '邮编',
                'property_features' => '房屋特征',
                'bedrooms' => '卧室数',
                'bathrooms' => '卫生间数',
                'square_footage' => '面积（平方英尺）',
                'parking_spaces' => '停车位数量',
                'parking_type' => '停车类型',
                'heating_type' => '供暖类型',
                'cooling_type' => '制冷类型',
                'laundry' => '洗衣设施',
                'laundry_options' => [
                    'in_unit' => '单元内',
                    'in_building' => '楼内',
                    'none' => '无',
                ],
                'furnished' => '家具齐全',
                'amenities' => '设施',
                'rental_info' => '出租信息',
                'availability_status' => '出租状态',
                'monthly_rent' => '月租金',
                'security_deposit' => '押金',
                'lease_term_type' => '租期类型',
                'min_lease_term' => '最短租期',
                'available_date' => '可入住日期',
                'utilities_included' => '包含的设施',
                'pet_policy' => '宠物政策',
                'pet_fee' => '宠物费用',
                'financial_info' => '财务信息',
                'management_fee_percentage' => '管理费比例',
                'annual_property_tax' => '年房产税',
                'maintenance_fund' => '维护基金',
                'hst_included' => '包含HST',
                'compliance_info' => '合规信息',
                'property_tax_id' => '房产税号',
                'rental_license_number' => '租赁许可证号',
                'insurance_policy_number' => '保险单号',
                'fire_safety_compliance' => '消防安全合规',
                'accessibility_compliance' => '无障碍设施合规',
                'last_inspection_date' => '最后检查日期',
                'media_upload' => '媒体上传',
                'upload_hint' => '支持图片、视频等文件格式',
                'update_property' => '更新房源',
                'save_property' => '保存房源',
                'cancel' => '取消',
                'set_as_cover' => '设为封面',
                
                // 操作
                'action_view' => '查看',
                'action_edit' => '编辑',
                'action_delete' => '删除',
                'action_application_management' => '申请管理',
                'action_media_management' => '媒体管理',
            
            // 选项值
            'property_types' => [
                'Apartment' => '公寓',
                'House' => '独立屋',
                'Townhouse' => '联排别墅',
                'Condo' => '共管公寓',
                'Basement' => '地下室',
                'Other' => '其他'
            ],
            
            'availability_statuses' => [
                'Available' => '可租',
                'Leased' => '已租',
                'Under Maintenance' => '维护中',
                'Reserved' => '已预订'
            ],
        ],
        
        // 租赁申请模块
        'application' => [
            'module_name' => '租赁申请',
            'list_page_title' => '租赁申请列表',
            'create_application' => '申请',
            
            // 申请信息
            'application_code' => '申请编号',
            'property' => '房源',
            'applicant' => '申请人',
            'employment' => '就业信息',
            'status' => '状态',
            'risk_score' => '风险评分',
            'reviewer' => '审核人',
            'files' => '文件',
            
            // 申请人信息
            'full_name' => '姓名',
            'email' => '邮箱',
            'phone' => '电话',
            'date_of_birth' => '出生日期',
            'address_line1' => '地址第一行',
            'address_line2' => '地址第二行',
            'state' => '州/省',
            'zip_code' => '邮编',
            'country' => '国家',
            
            // 就业信息
            'employer_name' => '雇主名称',
            'job_title' => '职位',
            'monthly_income' => '月收入',
            
            // 搜索字段
            'search_application_code' => '申请编号',
            'search_notes' => '备注',
            'search_email' => '邮箱',
            'search_phone' => '电话',
            
            // 筛选字段
            'filter_status' => '状态',
            'filter_reviewer' => '审核人',
            'filter_submitted_date' => '提交日期',
            'filter_reviewed_date' => '审核日期',
            'filter_property_type' => '房源类型',
            'filter_risk_score' => '风险评分',
            
            // 列标题
            'column_application_code' => '编号',
            'column_property' => '房源',
            'column_applicant' => '申请人',
            'column_employment' => '就业信息',
            'column_status' => '状态',
            'column_risk_score' => '风险评分',
            'column_reviewer' => '审核人',
            'column_files' => '文件',
            
            // 操作
            'action_view' => '查看',
            'action_edit' => '编辑',
            'action_review' => '审核',
            'action_delete' => '删除',
            
            // 模态框
            'modal_review_note_title' => '审核备注',
            'modal_review_note_placeholder' => '请输入审核备注...',
            'modal_save_note' => '保存备注',
            'modal_status_review_title' => '审核状态',
            'modal_new_status' => '新状态',
            'modal_review_notes_label' => '审核备注',
            'modal_review_notes_hint' => '请输入审核备注（可选）',
            'modal_save_changes' => '保存更改',
            
            // 状态值
            'application_statuses' => [
                'submitted' => '已提交',
                'under_review' => '审核中',
                'approved' => '已通过',
                'rejected' => '已拒绝'
            ],
        ],
        
        // 租约模块
        'lease' => [
            'module_name' => '租约管理',
            'page_title' => '租约列表',
            'create_title' => '新增租约',
            'edit_title' => '编辑租约',
            
            // 租约信息
            'lease_number' => '租约编号',
            'tenant' => '租客',
            'property' => '房源',
            'start_date' => '开始日期',
            'end_date' => '结束日期',
            'monthly_rent' => '月租金',
            'security_deposit' => '押金',
            'status' => '状态',
            
            // 状态值
            'lease_statuses' => [
                'draft' => '草稿',
                'active' => '生效中',
                'expired' => '已过期',
                'terminated' => '已终止'
            ],
        ],
        
        // 用户管理模块
        'user' => [
            'module_name' => '用户管理',
            'page_title' => '用户列表',
            'create_title' => '新增用户',
            'edit_title' => '编辑用户',
            
            // 用户信息
            'name' => '姓名',
            'email' => '邮箱',
            'role' => '角色',
            'status' => '状态',
            'created_at' => '创建时间',
            
            // 角色
            'roles' => [
                'admin' => '管理员',
                'manager' => '经理',
                'agent' => '经纪人',
                'user' => '用户'
            ],
        ],
        
        // 房东管理模块
        'owner' => [
            'module_name' => '房东管理',
            'page_title' => '房东列表',
            'create_title' => '新增房东',
            'edit_title' => '编辑房东',
            'create_label' => '新增房东',
            'export_label' => '导出房东',
            
            // 搜索字段
            'search_fields' => [
                'email' => '邮箱',
                'phone' => '电话',
            ],
            
            // 列标题
            'columns' => [
                'owner_name' => '房东姓名',
                'address' => '地址',
                'emergency_contact' => '紧急联系人',
                'tax_id' => '税号',
                'is_active' => '状态',
            ],
            
            // 操作
            'actions' => [
                'view' => '查看',
                'edit' => '编辑',
                'delete' => '删除',
            ],
        ],
        
        // 文件管理模块
        'file' => [
            'module_name' => '文件管理',
            'page_title' => '文件列表',
            'create_title' => '上传文件',
            'edit_title' => '编辑文件',
            'create_label' => '上传文件',
            'export_label' => '导出文件',
        ],
        
        // 权限管理模块
        'permission' => [
            'module_name' => '权限管理',
            'page_title' => '权限列表',
            'create_title' => '新增权限',
            'edit_title' => '编辑权限',
            'create_label' => '新增权限',
            'export_label' => '导出权限',
        ],
        
        // 租赁申请模块
        'application' => [
            'module_name' => '租赁申请',
            'page_title' => '租赁申请列表',
            'list_page_title' => '租赁申请列表',
            'create_title' => '新增租赁申请',
            'edit_title' => '编辑租赁申请',
            'create_label' => '新增申请',
            'export_label' => '导出申请',
            'create_application' => '新增申请',
            
            // 表单字段
            'application_info' => '申请信息',
            'property' => '房源',
            'select_property_placeholder' => '请选择房源',
            'application_code' => '申请编号',
            'applicant_info' => '申请人信息',
            'full_name' => '全名',
            'email' => '邮箱',
            'phone' => '电话',
            'date_of_birth' => '出生日期',
            'government_id_type' => '身份证件类型',
            'id_types' => [
                'passport' => '护照',
                'driver_license' => '驾照',
                'national_id' => '身份证',
            ],
            'ssn_last4' => '社会安全号后四位',
            'address_line1' => '地址第一行',
            'address_line2' => '地址第二行',
            'city' => '城市',
            'state' => '州/省',
            'zip_code' => '邮编',
            'country' => '国家',
            'emergency_contact_name' => '紧急联系人姓名',
            'emergency_contact_phone' => '紧急联系人电话',
            'insurance_provider' => '保险公司',
            'policy_number' => '保单号',
            'coverage_amount' => '保险金额',
            'employment_income' => '就业收入',
            'employer_name' => '雇主名称',
            'job_title' => '职位',
            'monthly_income' => '月收入',
            'other_income_source' => '其他收入来源',
            'income_verified_by' => '收入验证方式',
            'verification_methods' => [
                'manual' => '人工验证',
                'third_party' => '第三方验证',
            ],
            'verification_date' => '验证日期',
            'authorization_consent' => '授权同意',
            'credit_check_consent' => '信用检查同意',
            'background_check_consent' => '背景调查同意',
            'esignature_provider' => '电子签名提供商',
            'esignature_id' => '电子签名ID',
            'fair_housing_policy' => '公平住房政策',
            'cancel' => '取消',
            'update_application' => '更新申请',
            'submit_application' => '提交申请',
            'select_or_input_placeholder' => '请选择或输入',
            
            // 搜索字段
            'search_application_code' => '申请编号',
            'search_notes' => '备注',
            'search_email' => '邮箱',
            'search_phone' => '电话',
            
            // 筛选字段
            'filter_status' => '状态',
            'filter_reviewer' => '审核人',
            'filter_submitted_date' => '提交日期',
            'filter_reviewed_date' => '审核日期',
            'filter_property_type' => '房源类型',
            'filter_risk_score' => '风险评分',
            
            // 列标题
            'column_application_code' => '编号',
            'column_property' => '房源',
            'column_applicant' => '申请人',
            'column_employment' => '就业信息',
            'column_status' => '状态',
            'column_risk_score' => '风险评分',
            'column_reviewer' => '审核人',
            'column_files' => '文件',
            
            // 操作
            'action_view' => '查看',
            'action_edit' => '编辑',
            'action_review' => '审核',
            'action_delete' => '删除',
            
            // 模态框
            'modal_review_note_title' => '审核备注',
            'modal_review_note_placeholder' => '请输入审核备注...',
            'modal_save_note' => '保存备注',
            'modal_status_review_title' => '审核状态',
            'modal_new_status' => '新状态',
            'modal_review_notes_label' => '审核备注',
            'modal_review_notes_hint' => '请输入审核备注（可选）',
            'modal_save_changes' => '保存更改',
            
            // 状态值
            'application_statuses' => [
                'submitted' => '已提交',
                'under_review' => '审核中',
                'approved' => '已通过',
                'rejected' => '已拒绝'
            ],
            
            // 导出
            'export' => '导出',
            'export_excel' => '导出Excel',
            'export_pdf' => '导出PDF',
            
            // 批量操作
            'bulk_operations' => '批量操作',
            'bulk_delete' => '批量删除',
            'bulk_approve' => '批量审核通过',
            'bulk_reject' => '批量拒绝',
        ],
        
        // 租约管理模块
        'lease' => [
            'module_name' => '租约管理',
            'page_title' => '租约详情',
            'create_title' => '新增租约',
            'edit_title' => '编辑租约',
            'create_label' => '新增租约',
            'export_label' => '导出租约',
            
            // 操作
            'actions' => [
                'edit' => '编辑',
                'generate_pdf' => '生成PDF',
            ],
            
            // 章节
            'sections' => [
                'basic_info' => '基本信息',
                'tenant_info' => '租客信息',
                'property_info' => '房源信息',
            ],
            
            // 字段
            'fields' => [
                'lease_number' => '租约编号',
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
            
            // 搜索字段
            'search_fields' => [
                'lease_number' => '租约编号',
                'tenant' => '租客',
                'address' => '地址',
            ],
            
            // 筛选
            'filters' => [
                'status' => '状态',
                'start_date' => '开始日期',
                'monthly_rent' => '月租金',
            ],
            
            // 状态
            'statuses' => [
                'draft' => '草稿',
                'active' => '生效',
                'terminated' => '终止',
            ],
            
            // 列标题
            'columns' => [
                'lease_number' => '租约编号',
                'primary_tenant' => '主要租客',
                'property_address' => '房源地址',
                'rent' => '租金',
                'dates' => '租期',
                'status' => '状态',
            ],
            
            // 操作
            'actions' => [
                'view' => '查看',
                'edit' => '编辑',
                'download_pdf' => '下载PDF',
                'delete' => '删除',
            ],
        ],
        
        // 用户管理模块
        'user' => [
            'module_name' => '用户管理',
            'page_title' => '用户列表',
            'create_title' => '新增用户',
            'edit_title' => '编辑用户',
            'create_label' => '新增用户',
            'export_label' => '导出用户',
        ],
        
        // 回收站模块
        'trash' => [
            'module_name' => '回收站',
            'page_title' => '回收站',
            'create_title' => '恢复项目',
            'edit_title' => '编辑项目',
            'create_label' => '恢复',
            'export_label' => '导出',
            
            // 批量操作
            'bulk_action' => '批量操作',
            'bulk_restore' => '批量恢复',
            'bulk_force_delete' => '批量彻底删除',
            
            // 搜索字段
            'search_fields' => [
                'name' => '名称',
            ],
            
            // 筛选
            'filters' => [
                'module' => '模块',
                'deleted_by' => '删除人',
                'deleted_at' => '删除时间',
            ],
            
            // 模块
            'modules' => [
                'properties' => '房源',
                'owners' => '房东',
                'tenants' => '租客',
                'rentalApplications' => '租赁申请',
            ],
            
            // 操作
            'actions' => [
                'restore' => '恢复',
                'force_delete' => '彻底删除',
            ],
        ],
        
        // 申请人管理模块
        'applicant' => [
            'module_name' => '申请人管理',
            'page_title' => '申请人列表',
            'create_title' => '新增申请人',
            'edit_title' => '编辑申请人',
            'create_label' => '新增申请人',
            'export_label' => '导出申请人',
        ],
    ],
    
    // ===== 菜单翻译 =====
    'menu' => [
        'dashboard' => '仪表板',
        'rental' => '租赁管理',
        'leasing' => '租赁业务',
        'files' => '文件中心',
        'maintenance' => '维护管理',
        'financial' => '财务管理',
        'user' => '用户管理',
        'trash' => '回收站',
        'setting' => '系统设置',
        'logout' => '退出登录',
        
        // 租赁管理子菜单
        'properties' => '房源管理',
        'rental_owners' => '房东管理',
        'tenants' => '租户管理',
        'events' => '事件管理',
        
        // 租赁业务子菜单
        'applications' => '申请管理',
        'applicants' => '申请人管理',
        'draft_leases' => '租约草稿',
        'lease_renewals' => '租约续签',
        'active_leases' => '生效租约',
        'terminated_leases' => '已终止租约',
        'esignature_documents' => '电子签署文件',
        
        // 维护管理子菜单
        'work_orders' => '工单管理',
        'repairs' => '维修管理',
        'vendors' => '供应商管理',
        
        // 财务管理子菜单
        'income' => '收入管理',
        'expense' => '支出管理',
        'reports' => '财务报表',
        
        // 用户管理子菜单
        'users' => '用户管理',
        'roles' => '角色管理',
        'permissions' => '权限管理',
    ],
    
    // ===== 布局翻译 =====
    'layout' => [
        'default_title' => '物业管理系统',
        'app_name' => '物业管理系统',
        'welcome_message' => '欢迎使用物业管理系统',
        'search_placeholder' => '搜索...',
        'settings' => '系统设置',
        'profile' => '个人资料',
        'logout' => '退出登录',
        'close' => '关闭',
        'footer_text' => '物业管理系统. Powered by Laravel.',
    ],
    
    // ===== JavaScript翻译 =====
    'js' => [
        // 确认对话框
        'confirm_delete' => '确定要删除这条记录吗？',
        'confirm_batch_delete' => '确定要删除选中的记录吗？',
        'confirm_approve' => '确定要审核通过吗？',
        'confirm_reject' => '确定要拒绝吗？',
        
        // 操作结果
        'delete_success' => '删除成功',
        'delete_failed' => '删除失败',
        'save_success' => '保存成功',
        'save_failed' => '保存失败',
        'update_success' => '更新成功',
        'update_failed' => '更新失败',
        'approve_success' => '审核通过成功',
        'reject_success' => '拒绝成功',
        
        // 表单验证
        'required_field' => '此字段为必填项',
        'invalid_email' => '请输入有效的邮箱地址',
        'invalid_phone' => '请输入有效的电话号码',
        'invalid_date' => '请输入有效的日期',
        'invalid_number' => '请输入有效的数字',
        
        // 文件上传
        'upload_success' => '文件上传成功',
        'upload_failed' => '文件上传失败',
        'file_too_large' => '文件大小超出限制',
        'invalid_file_type' => '不支持的文件类型',
        
        // 搜索和筛选
        'search_placeholder' => '请输入搜索关键词...',
        'no_search_results' => '没有找到匹配的结果',
        'filter_applied' => '筛选已应用',
        'filter_cleared' => '筛选已清空',
        
        // 分页
        'loading_more' => '加载更多...',
        'no_more_data' => '没有更多数据',
        
        // 通用
        'loading' => '加载中...',
        'error_occurred' => '发生错误',
        'network_error' => '网络错误',
        'try_again' => '请重试',
    ],
]; 