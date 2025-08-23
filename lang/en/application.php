<?php
// lang/en/application.php
return [
    // 卡片标题
    'application_info' => 'Application Information',
    'applicant_info' => 'Applicant Information',
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
    'state' => 'State/Province',
    'zip_code' => 'Postal Code',
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
    'credit_check_consent' => 'I consent to credit check',
    'background_check_consent' => 'I consent to background check',
    'esignature_provider' => 'E-Signature Provider',
    'esignature_id' => 'E-Signature ID',
    'fair_housing_policy' => 'I understand and agree to non-discriminatory rental policy.',

    // 按钮
    'cancel' => 'Cancel',
    'submit_application' => 'Submit Application',
    'update_application' => 'Update Application',

    // 选项值
    'id_types' => [
        'SSN' => 'Social Security Number',
        'SIN' => 'Social Insurance Number',
        'ITIN' => 'Individual Taxpayer Identification Number'
    ],

    'verification_methods' => [
        'manual' => 'Manual Verification',
        'third_party' => 'Third Party Verification'
    ],

    // 列表页面
    'list_page_title' => 'Rental Applications',
    'create_application' => 'New Application',
    
    // 搜索字段
    'search_application_code' => 'Application Code',
    'search_notes' => 'Notes',
    'search_email' => 'Email',
    'search_phone' => 'Phone',
    
    // 筛选字段
    'filter_status' => 'Status',
    'filter_property' => 'Property',
    'filter_reviewer' => 'Reviewer',
    'filter_submitted_date' => 'Submitted Date',
    'filter_reviewed_date' => 'Reviewed Date',
    'filter_property_type' => 'Property Type',
    'filter_risk_score' => 'Risk Score',
    
    // 列标题
    'column_application_code' => 'Code',
    'column_property' => 'Property',
    'column_applicant' => 'Applicant',
    'column_employment' => 'Employment',
    'column_submitted_at' => 'Submitted',
    'column_reviewer' => 'Reviewer',
    'column_risk_score' => 'Risk Score',
    'column_review_notes' => 'Review Notes',
    'column_status' => 'Status',
    'column_files' => 'Files',
    'column_updated_at' => 'Updated',
    
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
    'modal_review_note_title' => 'Review Note',
    'modal_review_note_placeholder' => 'Enter review note...',
    'modal_save_note' => 'Save Note',
    'modal_status_review_title' => 'Review Status Change',
    'modal_new_status' => 'New Status',
    'modal_review_notes_label' => 'Review Notes',
    'modal_review_notes_hint' => 'Notes will be appended to existing content (timestamp automatically recorded)',
    'modal_save_changes' => 'Save Changes',

    // 消息提示
    'message_update_failed' => 'Status update failed',
];