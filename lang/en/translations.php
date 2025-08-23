<?php
/**
 * English Unified Translation File
 * Contains all module English translations
 */
return [
    // ===== Common Translations =====
    'common' => [
        // Basic operations
        'create' => 'Create',
        'edit' => 'Edit',
        'view' => 'View',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'submit' => 'Submit',
        'update' => 'Update',
        'refresh' => 'Refresh',
        'export' => 'Export',
        'import' => 'Import',
        'search' => 'Search',
        'filter' => 'Filter',
        'clear' => 'Clear',
        'reset' => 'Reset',
        'back' => 'Back',
        'close' => 'Close',
        'confirm' => 'Confirm',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'review' => 'Review',
        
        // Status
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
        'pending' => 'Pending',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        
        // Time
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'submitted_at' => 'Submitted At',
        'reviewed_at' => 'Reviewed At',
        
        // Pagination
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',
        'per_page' => 'per page',
        
        // Messages
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Info',
        'loading' => 'Loading...',
        'no_data' => 'No data available',
        'no_results' => 'No results found',
        
        // File operations
        'upload' => 'Upload',
        'download' => 'Download',
        'file_management' => 'File Management',
        'media_management' => 'Media Management',
        
        // Batch operations
        'batch_operations' => 'Batch Operations',
        'batch_delete' => 'Batch Delete',
        'batch_approve' => 'Batch Approve',
        'batch_reject' => 'Batch Reject',
        'batch_export' => 'Batch Export',
        
        // Export formats
        'export_excel' => 'Export Excel',
        'export_pdf' => 'Export PDF',
        'export_csv' => 'Export CSV',
    ],
    
    // ===== Module Translations =====
    'modules' => [
        // Property module
        'property' => [
            'module_name' => 'Property Management',
            'page_title' => 'Property List',
            'create_title' => 'Create Property',
            'edit_title' => 'Edit Property',
            'show_title' => 'Property Details',
            
            // Basic information
            'property_name' => 'Property Name',
            'property_type' => 'Property Type',
            'ownership_type' => 'Ownership Type',
            'year_built' => 'Year Built',
            'street_address' => 'Street Address',
            'city' => 'City',
            'province' => 'Province',
            'postal_code' => 'Postal Code',
            
            // Property features
            'bedrooms' => 'Bedrooms',
            'bathrooms' => 'Bathrooms',
            'square_footage' => 'Square Footage',
            'parking_spaces' => 'Parking Spaces',
            'parking_type' => 'Parking Type',
            
            // Rental information
            'availability_status' => 'Availability Status',
            'monthly_rent' => 'Monthly Rent',
            'security_deposit' => 'Security Deposit',
            'lease_term_type' => 'Lease Term Type',
            'available_date' => 'Available Date',
            
            // Search fields
            'search_property_name' => 'Property Name',
            'search_address' => 'Address',
            'search_city' => 'City',
            
            // Filter fields
            'filter_monthly_rent' => 'Monthly Rent',
            'filter_city' => 'City',
            'filter_property_type' => 'Property Type',
            'filter_bedrooms' => 'Bedrooms',
            'filter_square_footage' => 'Square Footage',
            'filter_parking_spaces' => 'Parking Spaces',
            
            // Column titles
            'column_property_name' => 'Property Name',
            'column_type' => 'Type',
            'column_bedrooms_bathrooms' => 'Bedrooms/Bathrooms',
            'column_rent' => 'Rent',
            'column_status' => 'Status',
            'column_owners' => 'Owners',
                            'column_features' => 'Features',
                
                // Form fields
                'basic_info' => 'Basic Information',
                'property_name' => 'Property Name',
                'property_type' => 'Property Type',
                'select_property_type' => 'Please select property type',
                'ownership_type' => 'Ownership Type',
                'year_built' => 'Year Built',
                'street_address' => 'Street Address',
                'city' => 'City',
                'province' => 'Province',
                'select_province' => 'Please select province',
                'postal_code' => 'Postal Code',
                'property_features' => 'Property Features',
                'bedrooms' => 'Bedrooms',
                'bathrooms' => 'Bathrooms',
                'square_footage' => 'Square Footage',
                'parking_spaces' => 'Parking Spaces',
                'parking_type' => 'Parking Type',
                'heating_type' => 'Heating Type',
                'cooling_type' => 'Cooling Type',
                'laundry' => 'Laundry',
                'laundry_options' => [
                    'in_unit' => 'In Unit',
                    'in_building' => 'In Building',
                    'none' => 'None',
                ],
                'furnished' => 'Furnished',
                'amenities' => 'Amenities',
                'rental_info' => 'Rental Information',
                'availability_status' => 'Availability Status',
                'monthly_rent' => 'Monthly Rent',
                'security_deposit' => 'Security Deposit',
                'lease_term_type' => 'Lease Term Type',
                'min_lease_term' => 'Minimum Lease Term',
                'available_date' => 'Available Date',
                'utilities_included' => 'Utilities Included',
                'pet_policy' => 'Pet Policy',
                'pet_fee' => 'Pet Fee',
                'financial_info' => 'Financial Information',
                'management_fee_percentage' => 'Management Fee Percentage',
                'annual_property_tax' => 'Annual Property Tax',
                'maintenance_fund' => 'Maintenance Fund',
                'hst_included' => 'HST Included',
                'compliance_info' => 'Compliance Information',
                'property_tax_id' => 'Property Tax ID',
                'rental_license_number' => 'Rental License Number',
                'insurance_policy_number' => 'Insurance Policy Number',
                'fire_safety_compliance' => 'Fire Safety Compliance',
                'accessibility_compliance' => 'Accessibility Compliance',
                'last_inspection_date' => 'Last Inspection Date',
                'media_upload' => 'Media Upload',
                'upload_hint' => 'Supports image, video and other file formats',
                'update_property' => 'Update Property',
                'save_property' => 'Save Property',
                'cancel' => 'Cancel',
                'set_as_cover' => 'Set as Cover',
                
                // Actions
                'action_view' => 'View',
                'action_edit' => 'Edit',
                'action_delete' => 'Delete',
                'action_application_management' => 'Application Management',
                'action_media_management' => 'Media Management',
            
            // Option values
            'property_types' => [
                'Apartment' => 'Apartment',
                'House' => 'House',
                'Townhouse' => 'Townhouse',
                'Condo' => 'Condo',
                'Basement' => 'Basement',
                'Other' => 'Other'
            ],
            
            'availability_statuses' => [
                'Available' => 'Available',
                'Leased' => 'Leased',
                'Under Maintenance' => 'Under Maintenance',
                'Reserved' => 'Reserved'
            ],
        ],
        
        // Rental application module
        'application' => [
            'module_name' => 'Rental Applications',
            'list_page_title' => 'Rental Application List',
            'create_application' => 'Apply',
            
            // Application information
            'application_code' => 'Application Code',
            'property' => 'Property',
            'applicant' => 'Applicant',
            'employment' => 'Employment',
            'status' => 'Status',
            'risk_score' => 'Risk Score',
            'reviewer' => 'Reviewer',
            'files' => 'Files',
            
            // Applicant information
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'date_of_birth' => 'Date of Birth',
            'address_line1' => 'Address Line 1',
            'address_line2' => 'Address Line 2',
            'state' => 'State/Province',
            'zip_code' => 'Zip Code',
            'country' => 'Country',
            
            // Employment information
            'employer_name' => 'Employer Name',
            'job_title' => 'Job Title',
            'monthly_income' => 'Monthly Income',
            
            // Search fields
            'search_application_code' => 'Application Code',
            'search_notes' => 'Notes',
            'search_email' => 'Email',
            'search_phone' => 'Phone',
            
            // Filter fields
            'filter_status' => 'Status',
            'filter_reviewer' => 'Reviewer',
            'filter_submitted_date' => 'Submitted Date',
            'filter_reviewed_date' => 'Reviewed Date',
            'filter_property_type' => 'Property Type',
            'filter_risk_score' => 'Risk Score',
            
            // Column titles
            'column_application_code' => 'Code',
            'column_property' => 'Property',
            'column_applicant' => 'Applicant',
            'column_employment' => 'Employment',
            'column_status' => 'Status',
            'column_risk_score' => 'Risk Score',
            'column_reviewer' => 'Reviewer',
            'column_files' => 'Files',
            
            // Actions
            'action_view' => 'View',
            'action_edit' => 'Edit',
            'action_review' => 'Review',
            'action_delete' => 'Delete',
            
            // Modals
            'modal_review_note_title' => 'Review Note',
            'modal_review_note_placeholder' => 'Enter review note...',
            'modal_save_note' => 'Save Note',
            'modal_status_review_title' => 'Review Status',
            'modal_new_status' => 'New Status',
            'modal_review_notes_label' => 'Review Notes',
            'modal_review_notes_hint' => 'Enter review notes (optional)',
            'modal_save_changes' => 'Save Changes',
            
            // Status values
            'application_statuses' => [
                'submitted' => 'Submitted',
                'under_review' => 'Under Review',
                'approved' => 'Approved',
                'rejected' => 'Rejected'
            ],
        ],
        
        // Lease module
        'lease' => [
            'module_name' => 'Lease Management',
            'page_title' => 'Lease List',
            'create_title' => 'Create Lease',
            'edit_title' => 'Edit Lease',
            
            // Lease information
            'lease_number' => 'Lease Number',
            'tenant' => 'Tenant',
            'property' => 'Property',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'monthly_rent' => 'Monthly Rent',
            'security_deposit' => 'Security Deposit',
            'status' => 'Status',
            
            // Status values
            'lease_statuses' => [
                'draft' => 'Draft',
                'active' => 'Active',
                'expired' => 'Expired',
                'terminated' => 'Terminated'
            ],
        ],
        
        // User management module
        'user' => [
            'module_name' => 'User Management',
            'page_title' => 'User List',
            'create_title' => 'Create User',
            'edit_title' => 'Edit User',
            
            // User information
            'name' => 'Name',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            
            // Roles
            'roles' => [
                'admin' => 'Administrator',
                'manager' => 'Manager',
                'agent' => 'Agent',
                'user' => 'User'
            ],
        ],
        
        // Owner management module
        'owner' => [
            'module_name' => 'Owner Management',
            'page_title' => 'Owner List',
            'create_title' => 'Create Owner',
            'edit_title' => 'Edit Owner',
            'create_label' => 'Create Owner',
            'export_label' => 'Export Owners',
            
            // Search fields
            'search_fields' => [
                'email' => 'Email',
                'phone' => 'Phone',
            ],
            
            // Column titles
            'columns' => [
                'owner_name' => 'Owner Name',
                'address' => 'Address',
                'emergency_contact' => 'Emergency Contact',
                'tax_id' => 'Tax ID',
                'is_active' => 'Status',
            ],
            
            // Actions
            'actions' => [
                'view' => 'View',
                'edit' => 'Edit',
                'delete' => 'Delete',
            ],
        ],
        
        // File management module
        'file' => [
            'module_name' => 'File Management',
            'page_title' => 'File List',
            'create_title' => 'Upload File',
            'edit_title' => 'Edit File',
            'create_label' => 'Upload File',
            'export_label' => 'Export Files',
        ],
        
        // Permission management module
        'permission' => [
            'module_name' => 'Permission Management',
            'page_title' => 'Permission List',
            'create_title' => 'Create Permission',
            'edit_title' => 'Edit Permission',
            'create_label' => 'Create Permission',
            'export_label' => 'Export Permissions',
        ],
        
        // Rental application module
        'application' => [
            'module_name' => 'Rental Application',
            'page_title' => 'Application List',
            'list_page_title' => 'Application List',
            'create_title' => 'Create Application',
            'edit_title' => 'Edit Application',
            'create_label' => 'Create Application',
            'export_label' => 'Export Applications',
            'create_application' => 'Create Application',
            
            // Form fields
            'application_info' => 'Application Information',
            'property' => 'Property',
            'select_property_placeholder' => 'Please select property',
            'application_code' => 'Application Code',
            'applicant_info' => 'Applicant Information',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'date_of_birth' => 'Date of Birth',
            'government_id_type' => 'Government ID Type',
            'id_types' => [
                'passport' => 'Passport',
                'driver_license' => 'Driver License',
                'national_id' => 'National ID',
            ],
            'ssn_last4' => 'SSN Last 4 Digits',
            'address_line1' => 'Address Line 1',
            'address_line2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State/Province',
            'zip_code' => 'Zip Code',
            'country' => 'Country',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_phone' => 'Emergency Contact Phone',
            'insurance_provider' => 'Insurance Provider',
            'policy_number' => 'Policy Number',
            'coverage_amount' => 'Coverage Amount',
            'employment_income' => 'Employment & Income',
            'employer_name' => 'Employer Name',
            'job_title' => 'Job Title',
            'monthly_income' => 'Monthly Income',
            'other_income_source' => 'Other Income Source',
            'income_verified_by' => 'Income Verified By',
            'verification_methods' => [
                'manual' => 'Manual Verification',
                'third_party' => 'Third Party Verification',
            ],
            'verification_date' => 'Verification Date',
            'authorization_consent' => 'Authorization & Consent',
            'credit_check_consent' => 'Credit Check Consent',
            'background_check_consent' => 'Background Check Consent',
            'esignature_provider' => 'E-Signature Provider',
            'esignature_id' => 'E-Signature ID',
            'fair_housing_policy' => 'Fair Housing Policy',
            'cancel' => 'Cancel',
            'update_application' => 'Update Application',
            'submit_application' => 'Submit Application',
            'select_or_input_placeholder' => 'Please select or input',
            
            // Search fields
            'search_application_code' => 'Application Code',
            'search_notes' => 'Notes',
            'search_email' => 'Email',
            'search_phone' => 'Phone',
            
            // Filter fields
            'filter_status' => 'Status',
            'filter_reviewer' => 'Reviewer',
            'filter_submitted_date' => 'Submitted Date',
            'filter_reviewed_date' => 'Reviewed Date',
            'filter_property_type' => 'Property Type',
            'filter_risk_score' => 'Risk Score',
            
            // Column titles
            'column_application_code' => 'Code',
            'column_property' => 'Property',
            'column_applicant' => 'Applicant',
            'column_employment' => 'Employment',
            'column_status' => 'Status',
            'column_risk_score' => 'Risk Score',
            'column_reviewer' => 'Reviewer',
            'column_files' => 'Files',
            
            // Actions
            'action_view' => 'View',
            'action_edit' => 'Edit',
            'action_review' => 'Review',
            'action_delete' => 'Delete',
            
            // Modals
            'modal_review_note_title' => 'Review Note',
            'modal_review_note_placeholder' => 'Please enter review note...',
            'modal_save_note' => 'Save Note',
            'modal_status_review_title' => 'Review Status',
            'modal_new_status' => 'New Status',
            'modal_review_notes_label' => 'Review Notes',
            'modal_review_notes_hint' => 'Please enter review notes (optional)',
            'modal_save_changes' => 'Save Changes',
            
            // Status values
            'application_statuses' => [
                'submitted' => 'Submitted',
                'under_review' => 'Under Review',
                'approved' => 'Approved',
                'rejected' => 'Rejected'
            ],
            
            // Export
            'export' => 'Export',
            'export_excel' => 'Export Excel',
            'export_pdf' => 'Export PDF',
            
            // Bulk operations
            'bulk_operations' => 'Bulk Operations',
            'bulk_delete' => 'Bulk Delete',
            'bulk_approve' => 'Bulk Approve',
            'bulk_reject' => 'Bulk Reject',
        ],
        
        // Lease management module
        'lease' => [
            'module_name' => 'Lease Management',
            'page_title' => 'Lease Details',
            'create_title' => 'Create Lease',
            'edit_title' => 'Edit Lease',
            'create_label' => 'Create Lease',
            'export_label' => 'Export Leases',
            
            // Actions
            'actions' => [
                'edit' => 'Edit',
                'generate_pdf' => 'Generate PDF',
            ],
            
            // Sections
            'sections' => [
                'basic_info' => 'Basic Information',
                'tenant_info' => 'Tenant Information',
                'property_info' => 'Property Information',
            ],
            
            // Fields
            'fields' => [
                'lease_number' => 'Lease Number',
                'start_date' => 'Start Date',
                'end_date' => 'End Date',
                'status' => 'Status',
                'tenant' => 'Tenant',
                'phone' => 'Phone',
                'email' => 'Email',
                'property' => 'Property',
                'address' => 'Address',
                'monthly_rent' => 'Monthly Rent',
            ],
            
            // Search fields
            'search_fields' => [
                'lease_number' => 'Lease Number',
                'tenant' => 'Tenant',
                'address' => 'Address',
            ],
            
            // Filters
            'filters' => [
                'status' => 'Status',
                'start_date' => 'Start Date',
                'monthly_rent' => 'Monthly Rent',
            ],
            
            // Statuses
            'statuses' => [
                'draft' => 'Draft',
                'active' => 'Active',
                'terminated' => 'Terminated',
            ],
            
            // Columns
            'columns' => [
                'lease_number' => 'Lease Number',
                'primary_tenant' => 'Primary Tenant',
                'property_address' => 'Property Address',
                'rent' => 'Rent',
                'dates' => 'Dates',
                'status' => 'Status',
            ],
            
            // Actions
            'actions' => [
                'view' => 'View',
                'edit' => 'Edit',
                'download_pdf' => 'Download PDF',
                'delete' => 'Delete',
            ],
        ],
        
        // User management module
        'user' => [
            'module_name' => 'User Management',
            'page_title' => 'User List',
            'create_title' => 'Create User',
            'edit_title' => 'Edit User',
            'create_label' => 'Create User',
            'export_label' => 'Export Users',
        ],
        
        // Trash module
        'trash' => [
            'module_name' => 'Trash',
            'page_title' => 'Trash',
            'create_title' => 'Restore Item',
            'edit_title' => 'Edit Item',
            'create_label' => 'Restore',
            'export_label' => 'Export',
            
            // Bulk actions
            'bulk_action' => 'Bulk Actions',
            'bulk_restore' => 'Bulk Restore',
            'bulk_force_delete' => 'Bulk Force Delete',
            
            // Search fields
            'search_fields' => [
                'name' => 'Name',
            ],
            
            // Filters
            'filters' => [
                'module' => 'Module',
                'deleted_by' => 'Deleted By',
                'deleted_at' => 'Deleted At',
            ],
            
            // Modules
            'modules' => [
                'properties' => 'Properties',
                'owners' => 'Owners',
                'tenants' => 'Tenants',
                'rentalApplications' => 'Rental Applications',
            ],
            
            // Actions
            'actions' => [
                'restore' => 'Restore',
                'force_delete' => 'Force Delete',
            ],
        ],
        
        // Applicant management module
        'applicant' => [
            'module_name' => 'Applicant Management',
            'page_title' => 'Applicant List',
            'create_title' => 'Create Applicant',
            'edit_title' => 'Edit Applicant',
            'create_label' => 'Create Applicant',
            'export_label' => 'Export Applicants',
        ],
    ],
    
    // ===== Menu Translations =====
    'menu' => [
        'dashboard' => 'Dashboard',
        'rental' => 'Rental Management',
        'leasing' => 'Leasing Business',
        'files' => 'File Center',
        'maintenance' => 'Maintenance Management',
        'financial' => 'Financial Management',
        'user' => 'User Management',
        'trash' => 'Trash',
        'setting' => 'System Settings',
        'logout' => 'Logout',
        
        // Rental management submenu
        'properties' => 'Property Management',
        'rental_owners' => 'Landlord Management',
        'tenants' => 'Tenant Management',
        'events' => 'Event Management',
        
        // Leasing business submenu
        'applications' => 'Application Management',
        'applicants' => 'Applicant Management',
        'draft_leases' => 'Draft Leases',
        'lease_renewals' => 'Lease Renewals',
        'active_leases' => 'Active Leases',
        'terminated_leases' => 'Terminated Leases',
        'esignature_documents' => 'E-Signature Documents',
        
        // Maintenance management submenu
        'work_orders' => 'Work Orders',
        'repairs' => 'Repair Management',
        'vendors' => 'Vendor Management',
        
        // Financial management submenu
        'income' => 'Income Management',
        'expense' => 'Expense Management',
        'reports' => 'Financial Reports',
        
        // User management submenu
        'users' => 'User Management',
        'roles' => 'Role Management',
        'permissions' => 'Permission Management',
    ],
    
    // ===== Layout Translations =====
    'layout' => [
        'default_title' => 'Property Management System',
        'app_name' => 'Property Management System',
        'welcome_message' => 'Welcome to Property Management System',
        'search_placeholder' => 'Search...',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'logout' => 'Logout',
        'close' => 'Close',
        'footer_text' => 'Property Management System. Powered by Laravel.',
    ],
    
    // ===== JavaScript Translations =====
    'js' => [
        // Confirmation dialogs
        'confirm_delete' => 'Are you sure you want to delete this record?',
        'confirm_batch_delete' => 'Are you sure you want to delete the selected records?',
        'confirm_approve' => 'Are you sure you want to approve?',
        'confirm_reject' => 'Are you sure you want to reject?',
        
        // Operation results
        'delete_success' => 'Deleted successfully',
        'delete_failed' => 'Delete failed',
        'save_success' => 'Saved successfully',
        'save_failed' => 'Save failed',
        'update_success' => 'Updated successfully',
        'update_failed' => 'Update failed',
        'approve_success' => 'Approved successfully',
        'reject_success' => 'Rejected successfully',
        
        // Form validation
        'required_field' => 'This field is required',
        'invalid_email' => 'Please enter a valid email address',
        'invalid_phone' => 'Please enter a valid phone number',
        'invalid_date' => 'Please enter a valid date',
        'invalid_number' => 'Please enter a valid number',
        
        // File upload
        'upload_success' => 'File uploaded successfully',
        'upload_failed' => 'File upload failed',
        'file_too_large' => 'File size exceeds limit',
        'invalid_file_type' => 'Unsupported file type',
        
        // Search and filter
        'search_placeholder' => 'Enter search keywords...',
        'no_search_results' => 'No matching results found',
        'filter_applied' => 'Filter applied',
        'filter_cleared' => 'Filter cleared',
        
        // Pagination
        'loading_more' => 'Loading more...',
        'no_more_data' => 'No more data',
        
        // Common
        'loading' => 'Loading...',
        'error_occurred' => 'An error occurred',
        'network_error' => 'Network error',
        'try_again' => 'Please try again',
    ],
]; 