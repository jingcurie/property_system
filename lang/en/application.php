<?php
// lang/en/application.php
return [
    // 卡片标题
    'application_info' => 'Application Info',
    'applicant_info' => 'Applicant Info',
    'employment_income' => 'Employment & Income',
    'authorization_consent' => 'Authorization & Consent',

    // 申请信息字段
    'property' => 'Property',
    'application_code' => 'Application Code',
    'select_property_placeholder' => 'Please select a property',
    'select_or_input_placeholder' => 'Please select or input...',

    // 申请人信息字段
    'full_name' => 'Full Name',
    'email' => 'Email',
    'phone' => 'Phone',
    'date_of_birth' => 'Date of Birth',
    'government_id_type' => 'Government ID Type',
    'ssn_last4' => 'SSN Last 4',
    'address_line1' => 'Address Line 1',
    'address_line2' => 'Address Line 2',
    'city' => 'City',
    'state' => 'State',
    'zip_code' => 'ZIP Code',
    'country' => 'Country',
    'emergency_contact_name' => 'Emergency Contact Name',
    'emergency_contact_phone' => 'Emergency Contact Phone',
    'insurance_provider' => 'Insurance Provider',
    'policy_number' => 'Policy Number',
    'coverage_amount' => 'Coverage Amount',

    // 就业信息字段
    'employer_name' => 'Employer Name',
    'job_title' => 'Job Title',
    'monthly_income' => 'Monthly Income',
    'other_income_source' => 'Other Income Source',
    'income_verified_by' => 'Income Verified By',
    'verification_date' => 'Verification Date',

    // 授权同意字段
    'credit_check_consent' => 'I authorize credit check',
    'background_check_consent' => 'I authorize background check',
    'esignature_provider' => 'E-sign Provider',
    'esignature_id' => 'E-signature ID',
    'fair_housing_policy' => 'I understand and agree to the non-discrimination rental policy.',

    // 按钮
    'cancel' => 'Cancel',
    'submit_application' => 'Submit Application',
    'update_application' => 'Update Application',

    // 选项值
    'id_types' => [
        'SSN' => 'SSN',
        'SIN' => 'SIN',
        'ITIN' => 'ITIN'
    ],

    'verification_methods' => [
        'manual' => 'Manual',
        'third_party' => 'Third Party'
    ],

    // 列表页面
    'list_page_title' => 'Rental Applications',
    'create_application' => 'Application',
    
    // 搜索字段
    'search_application_code' => 'Application Code',
    'search_notes' => 'Notes',
    
    // 筛选字段
    'filter_status' => 'Status',
    'filter_property' => 'Property',
    
    // 列标题
    'column_application_code' => 'Code',
    'column_property' => 'Property',
    'column_applicant' => 'Applicant',
    'column_lease_term' => 'Lease Term (Months)',
    'column_submitted_at' => 'Submitted At',
    'column_reviewer' => 'Reviewer',
    'column_review_notes' => 'Review Notes',
    'column_status' => 'Status',
    'column_updated_at' => 'Status Updated At',
    
    // 操作按钮
    'action_view' => 'View',
    'action_edit' => 'Edit',
    'action_delete' => 'Delete',
    'action_review' => 'Review',

    // 状态选项
    'status_submitted' => 'Submitted',
    'status_under_review' => 'Under Review',
    'status_approved' => 'Approved',
    'status_rejected' => 'Rejected',

    // 模态框
    'modal_review_note_title' => 'Review Notes',
    'modal_review_note_placeholder' => 'Enter review notes...',
    'modal_save_note' => 'Save Note',
    'modal_status_review_title' => 'Review Status Change',
    'modal_new_status' => 'New Status',
    'modal_review_notes_label' => 'Review Notes',
    'modal_review_notes_hint' => 'Notes will be appended to existing content (system automatically records time)',
    'modal_save_changes' => 'Save Changes',

    // 消息提示
    'message_update_failed' => 'Status update failed',
];