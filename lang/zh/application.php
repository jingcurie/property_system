<?php
// lang/zh/application.php
return [
    // 卡片标题
    'application_info' => '申请信息',
    'applicant_info' => '申请人信息',
    'employment_income' => '就业与收入',
    'authorization_consent' => '授权与同意',

    // 申请信息字段
    'property' => '房源',
    'application_code' => '申请编号',
    'select_property_placeholder' => '请选择房源',
    'select_or_input_placeholder' => '请选择或输入...',

    // 申请人信息字段
    'full_name' => '姓名',
    'email' => '邮箱',
    'phone' => '电话',
    'date_of_birth' => '出生日期',
    'government_id_type' => '政府证件类型',
    'ssn_last4' => 'SSN 后四位',
    'address_line1' => '地址第一行',
    'address_line2' => '地址第二行',
    'city' => '城市',
    'state' => '州/省',
    'zip_code' => '邮编',
    'country' => '国家',
    'emergency_contact_name' => '紧急联系人姓名',
    'emergency_contact_phone' => '紧急联系人电话',
    'insurance_provider' => '保险提供商',
    'policy_number' => '保单号',
    'coverage_amount' => '保险金额',

    // 就业信息字段
    'employer_name' => '雇主名称',
    'job_title' => '职位',
    'monthly_income' => '月收入',
    'other_income_source' => '其他收入来源',
    'income_verified_by' => '收入验证方式',
    'verification_date' => '验证日期',

    // 授权同意字段
    'credit_check_consent' => '我同意进行信用检查',
    'background_check_consent' => '我同意进行背景调查',
    'esignature_provider' => '电子签名提供商',
    'esignature_id' => '电子签名ID',
    'fair_housing_policy' => '我理解并同意非歧视租赁政策。',

    // 按钮
    'cancel' => '取消',
    'submit_application' => '提交申请',
    'update_application' => '更新申请',

    // 选项值
    'id_types' => [
        'SSN' => '社会安全号',
        'SIN' => '社会保险号',
        'ITIN' => '个人纳税识别号'
    ],

    'verification_methods' => [
        'manual' => '手动验证',
        'third_party' => '第三方验证'
    ],

    // 列表页面
    'list_page_title' => '租赁申请列表',
    'create_application' => '申请',
    
    // 搜索字段
    'search_application_code' => '申请编号',
    'search_notes' => '备注',
    
    // 筛选字段
    'filter_status' => '状态',
    'filter_property' => '房源',
    
    // 列标题
    'column_application_code' => '编号',
    'column_property' => '房源',
    'column_applicant' => '申请人',
    'column_lease_term' => '租期(月)',
    'column_submitted_at' => '申请时间',
    'column_reviewer' => '审核人',
    'column_review_notes' => '审核备注',
    'column_status' => '状态',
    'column_updated_at' => '状态更新时间',
    
    // 操作按钮
    'action_view' => '查看',
    'action_edit' => '编辑',
    'action_delete' => '删除',
    'action_review' => '审核',

    // 状态选项
    'status_submitted' => '已提交',
    'status_under_review' => '审核中',
    'status_approved' => '已通过',
    'status_rejected' => '已拒绝',

    // 模态框
    'modal_review_note_title' => '审核备注',
    'modal_review_note_placeholder' => '输入审核备注...',
    'modal_save_note' => '保存备注',
    'modal_status_review_title' => '审核状态变更',
    'modal_new_status' => '新状态',
    'modal_review_notes_label' => '审核备注',
    'modal_review_notes_hint' => '备注将追加至现有内容（系统自动记录时间）',
    'modal_save_changes' => '保存修改',

    // 消息提示
    'message_update_failed' => '状态更新失败',

];