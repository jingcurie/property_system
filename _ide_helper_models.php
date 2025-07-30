<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $amenity_id Auto-incremented amenity ID
 * @property int|null $property_id
 * @property int|null $has_gym Whether the property has a gym
 * @property int|null $has_pool Whether the property has a swimming pool
 * @property int|null $has_balcony Whether the property has a balcony
 * @property int|null $has_elevator Whether the building has an elevator
 * @property int|null $has_dishwasher Whether the unit includes a dishwasher
 * @property int|null $has_fridge Whether the unit includes a fridge
 * @property int|null $has_stove Whether the unit includes a stove
 * @property int|null $has_microwave Whether the unit includes a microwave
 * @property int|null $has_air_conditioning Whether the unit has air conditioning
 * @property string $created_at Record creation time
 * @property string $updated_at Record last update time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereAmenityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasAirConditioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasBalcony($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasDishwasher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasElevator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasFridge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasGym($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasMicrowave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasStove($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereUpdatedAt($value)
 */
	class Amenity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rental_application_id
 * @property string $full_name Full name of the applicant
 * @property string $email Email address
 * @property string $phone Phone number
 * @property string $date_of_birth Date of birth
 * @property string|null $government_id_type Type of government-issued ID
 * @property string|null $ssn_last4 Last 4 digits of SSN/SIN/ITIN
 * @property string $address_line1 Primary address line
 * @property string|null $address_line2 Secondary address line (optional)
 * @property string $city City of residence
 * @property string $state Province/State code
 * @property string $zip_code Postal code
 * @property string $country Country code (default Canada)
 * @property string $emergency_contact_name Name of emergency contact
 * @property string $emergency_contact_phone Phone number of emergency contact
 * @property string|null $renters_insurance_provider Insurance company name
 * @property string|null $policy_number Insurance policy number
 * @property string|null $coverage_amount Insurance coverage amount
 * @property string|null $ip_address IP address at submission
 * @property string|null $device_fingerprint Browser/device fingerprint
 * @property int|null $previous_application_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $deleted_at
 * @property int $deleted_by
 * @property-read \App\Models\EmploymentDetail|null $employmentDetail
 * @property-read \App\Models\RentalApplication|null $previousApplication
 * @property-read \App\Models\RentalApplication $rentalApplication
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereCoverageAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereDeviceFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereEmergencyContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereEmergencyContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereGovernmentIdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant wherePolicyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant wherePreviousApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereRentalApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereRentersInsuranceProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereSsnLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Applicant whereZipCode($value)
 */
	class Applicant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $compliance_id Auto-incremented compliance record ID
 * @property int|null $property_id
 * @property string|null $property_tax_id Property tax ID number
 * @property string|null $rental_license_number Government-issued rental license number
 * @property string|null $insurance_policy_number Insurance policy covering the property
 * @property int|null $fire_safety_compliance Passed fire safety inspection
 * @property int|null $accessibility_compliance Compliant with accessibility regulations
 * @property string|null $last_inspection_date Date of last official or third-party inspection
 * @property \Illuminate\Support\Carbon $created_at Record creation time
 * @property \Illuminate\Support\Carbon $updated_at Record last updated time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereAccessibilityCompliance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereComplianceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereFireSafetyCompliance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereInsurancePolicyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereLastInspectionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo wherePropertyTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereRentalLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereUpdatedAt($value)
 */
	class ComplianceInfo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rental_application_id
 * @property int $credit_check_consent Consent for credit check
 * @property int $background_check_consent Consent for background check
 * @property string|null $signed_at Time of digital signature
 * @property string|null $esignature_provider E-signature provider (e.g., DocuSign)
 * @property string|null $esignature_id Reference ID from the e-sign platform
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RentalApplication $rentalApplication
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereBackgroundCheckConsent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereCreditCheckConsent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereEsignatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereEsignatureProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereRentalApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereSignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consent whereUpdatedAt($value)
 */
	class Consent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $applicant_id
 * @property string $employer_name Employer name
 * @property string $job_title Job title/position
 * @property string $monthly_income Monthly income amount
 * @property array<array-key, mixed>|null $income_proof_files Attached income documents (JSON array)
 * @property string|null $other_income_source Description of other income sources
 * @property string|null $income_verified_by Verification method
 * @property string|null $verification_date Date of income verification
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Applicant $applicant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereApplicantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereEmployerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereIncomeProofFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereIncomeVerifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereMonthlyIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereOtherIncomeSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentDetail whereVerificationDate($value)
 */
	class EmploymentDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id Primary key ID
 * @property string|null $title
 * @property string $filename Original file name
 * @property string $path Relative storage path
 * @property string|null $mime_type MIME type, e.g., image/png, application/pdf
 * @property int $size File size in bytes
 * @property string $disk Storage disk, e.g., local, s3
 * @property string $fileable_type Associated model class, e.g., App\Models\RentalApplication
 * @property int $fileable_id Associated model ID
 * @property string|null $envelope_id
 * @property string|null $category Optional tag, e.g., contract, photo, idcard
 * @property string|null $description Short description or note
 * @property int $is_cover
 * @property int $sort_order
 * @property int $is_private Whether the file is private
 * @property int|null $uploaded_by Uploader user ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property string|null $signature_status
 * @property string|null $lease_document_type 租赁文档类型（仅当 fileable_type=lease 时使用）
 * @property int $requires_signature 是否需要签名
 * @property int $tenant_signed 租户是否已签名
 * @property string|null $tenant_signed_date 租户签名日期
 * @property int $landlord_signed 房东是否已签名
 * @property string|null $landlord_signed_date 房东签名日期
 * @property string|null $document_version 文档版本
 * @property int|null $superseded_by 被哪个文档替代（files.id）
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $fileable
 * @property-read \App\Models\User|null $uploader
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDocumentVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereEnvelopeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereIsCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereLandlordSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereLandlordSignedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereLeaseDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereRequiresSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSignatureStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereSupersededBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereTenantSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereTenantSignedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUploadedBy($value)
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $financial_id Auto-incremented financial info ID
 * @property int|null $property_id
 * @property string|null $management_fee_percentage Management fee rate in percentage (e.g. 8.50)
 * @property string|null $annual_property_tax Yearly property tax amount
 * @property int|null $hst_included Is HST (sales tax) included in rent?
 * @property string|null $maintenance_fund Reserve fund for ongoing maintenance
 * @property \Illuminate\Support\Carbon $created_at Record creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last modified timestamp
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereAnnualPropertyTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereFinancialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereHstIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereMaintenanceFund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereManagementFeePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereUpdatedAt($value)
 */
	class FinancialInfo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $lease_id
 * @property string $lease_number 租赁编号，如 L2025001
 * @property string|null $lease_group_id 租赁组ID，处理续约关系
 * @property int $version_number 版本号（续约递增）
 * @property int $property_id 房产ID
 * @property string $lease_type 租赁类型
 * @property \Illuminate\Support\Carbon $start_date 开始日期
 * @property \Illuminate\Support\Carbon|null $end_date 结束日期
 * @property numeric $monthly_rent 月租金
 * @property int $rent_due_day 租金到期日
 * @property numeric $late_fee_amount 滞纳金金额
 * @property int $late_fee_grace_days 滞纳金宽限天数
 * @property numeric $nsf_fee NSF费用
 * @property numeric $security_deposit 保证金
 * @property numeric $furniture_deposit 家具押金
 * @property numeric $pet_deposit 宠物押金
 * @property string|null $utilities_included 包含的公用事业
 * @property bool $pets_allowed 是否允许宠物
 * @property bool $smoking_allowed 是否允许吸烟
 * @property bool $subletting_allowed 是否允许转租
 * @property bool $tenant_insurance_required 是否需要租户保险
 * @property numeric|null $minimum_coverage_amount 最低保险金额
 * @property string $status 租赁状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property bool $furnished
 * @property numeric|null $cleaning_fee
 * @property bool $insurance_required
 * @property string|null $termination_policy
 * @property string|null $parking_info
 * @property string|null $storage_info
 * @property bool $strata_acknowledged
 * @property bool $form_k_signed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeaseFeeStructure> $feeStructures
 * @property-read int|null $fee_structures_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Property $property
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenant> $tenants
 * @property-read int|null $tenants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease past()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereCleaningFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereFormKSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereFurnished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereFurnitureDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereInsuranceRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLateFeeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLateFeeGraceDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereMinimumCoverageAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereMonthlyRent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereNsfFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereParkingInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease wherePetDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease wherePetsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereRentDueDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereSecurityDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereSmokingAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStorageInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStrataAcknowledged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereSublettingAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereTenantInsuranceRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereTerminationPolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereUtilitiesIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereVersionNumber($value)
 */
	class Lease extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $lease_id 租赁ID
 * @property string $unit_type 户型：1 Bdrm, 2 Bdrm 1 Bath等
 * @property string|null $mandatory_cleaning_fee 强制清洁费
 * @property int $cleaning_fee_paid 清洁费是否已付
 * @property string $move_out_inspection_fee 搬出检查费
 * @property string|null $move_in_fee 搬入费
 * @property string|null $move_out_fee 搬出费
 * @property int $elevator_booking_required 是否需要预约电梯
 * @property int $elevator_booking_notice_days 电梯预约提前天数
 * @property string $key_deposit 钥匙押金
 * @property string $fob_deposit 门禁卡押金
 * @property string $key_loan_fee_regular 常规时间借钥匙费
 * @property string $key_loan_fee_after_hours 非工作时间借钥匙费
 * @property string|null $lease_break_fee_half_month 违约费-半月（租户找替换者）
 * @property string|null $lease_break_fee_one_month 违约费-一月（房东找替换者）
 * @property string|null $lease_break_fee_two_month 违约费-两月（立即解约）
 * @property string $created_at
 * @property-read \App\Models\Lease $lease
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereCleaningFeePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereElevatorBookingNoticeDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereElevatorBookingRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereFobDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereKeyDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereKeyLoanFeeAfterHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereKeyLoanFeeRegular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseBreakFeeHalfMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseBreakFeeOneMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseBreakFeeTwoMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereLeaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMandatoryCleaningFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMoveInFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMoveOutFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereMoveOutInspectionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LeaseFeeStructure whereUnitType($value)
 */
	class LeaseFeeStructure extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $marketing_id Auto-incremented marketing record ID
 * @property int|null $property_id
 * @property string $platform Marketing platform name
 * @property string|null $listing_url External link to the listing on the platform
 * @property string|null $listing_date Date the property was listed on this platform
 * @property int|null $is_active Whether the listing is currently active
 * @property string|null $seo_keywords Optional SEO keywords to enhance property searchability
 * @property string|null $description English description for platform or SEO use
 * @property \Illuminate\Support\Carbon $created_at Record creation time
 * @property \Illuminate\Support\Carbon $updated_at Record last update time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereListingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereListingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereMarketingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereSeoKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereUpdatedAt($value)
 */
	class Marketing extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $notification_id Auto-incremented notification ID
 * @property int $user_id Recipient user ID
 * @property string $type
 * @property string $title Notification title
 * @property string $content Notification content
 * @property int|null $is_read Read status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $owner_id Auto-incremented owner ID
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string|null $emergency_contact
 * @property string|null $emergency_contact_phone
 * @property string|null $address
 * @property string|null $tax_id
 * @property string|null $notes
 * @property int|null $is_active Soft delete flag
 * @property string|null $deleted_at When record was soft deleted
 * @property int|null $deleted_by User who performed soft delete
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyOwnership> $ownerships
 * @property-read int|null $ownerships_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Property> $properties
 * @property-read int|null $properties_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereEmergencyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereEmergencyContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Owner whereTaxId($value)
 */
	class Owner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $property_id
 * @property string $property_code 业务编码
 * @property string|null $property_name Marketing name for the property
 * @property string $property_type Type of property
 * @property string $ownership_type Ownership structure
 * @property int|null $year_built Year the property was constructed
 * @property string $address_street Street address
 * @property string $address_city City
 * @property string $address_province Province (AB, BC, ON, etc.)
 * @property string $address_postal_code Postal code
 * @property string|null $latitude Geolocation latitude
 * @property string|null $longitude Geolocation longitude
 * @property int|null $total_floors Total number of floors (for buildings)
 * @property string|null $description English description for listings
 * @property bool|null $is_active Soft delete flag
 * @property \Illuminate\Support\Carbon $created_at Record creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last update timestamp
 * @property string|null $deleted_at When record was soft deleted
 * @property int|null $deleted_by User who performed soft delete
 * @property-read \App\Models\Amenity|null $amenity
 * @property-read \App\Models\ComplianceInfo|null $complianceInfo
 * @property-read \App\Models\PropertyFeature|null $feature
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\FinancialInfo|null $financialInfo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lease> $leases
 * @property-read int|null $leases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Marketing> $marketing
 * @property-read int|null $marketing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Owner> $owners
 * @property-read int|null $owners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyOwnership> $ownerships
 * @property-read int|null $ownerships_count
 * @property-read \App\Models\RentalInfo|null $rentalInfo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereOwnershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereTotalFloors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereYearBuilt($value)
 */
	class Property extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $feature_id Auto-incremented feature ID
 * @property int|null $property_id
 * @property int $bedrooms Number of bedrooms
 * @property string $bathrooms Number of bathrooms (0.5 for half baths)
 * @property int|null $square_footage Total area in square feet
 * @property int|null $parking_spaces Number of parking spaces included
 * @property string|null $parking_type Type of parking available
 * @property string|null $heating_type Heating system type (forced air, radiant, etc.)
 * @property string|null $cooling_type Cooling system type (central AC, window units, etc.)
 * @property int|null $furnished Whether the unit comes furnished
 * @property string $laundry
 * @property int|null $is_active Soft delete flag
 * @property string|null $deleted_at When record was soft deleted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereBedrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereCoolingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereFeatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereFurnished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereHeatingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereLaundry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereParkingSpaces($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereParkingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereSquareFootage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereUpdatedAt($value)
 */
	class PropertyFeature extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $media_id Auto-incremented media ID
 * @property int|null $property_id
 * @property string $media_type Media type
 * @property string $file_path Path to media file
 * @property string|null $file_type MIME type
 * @property int|null $is_cover Whether this media is the cover
 * @property int|null $uploaded_by User ID who uploaded
 * @property \Illuminate\Support\Carbon $created_at Upload timestamp
 * @property string $upload_at Upload timestamp
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $sort_order
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereIsCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereMediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereUploadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereUploadedBy($value)
 */
	class PropertyMedia extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $ownership_id Auto-incremented ownership ID
 * @property int|null $property_id
 * @property int $owner_id Reference to owner
 * @property string|null $ownership_percentage Percentage ownership
 * @property int $is_primary
 * @property string|null $start_date When ownership began
 * @property string|null $end_date When ownership ended
 * @property \Illuminate\Support\Carbon $created_at Record creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \App\Models\Owner $owner
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereOwnershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereOwnershipPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereUpdatedAt($value)
 */
	class PropertyOwnership extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int|null $property_id
 * @property int|null $required_min_credit_score Minimum credit score required
 * @property string|null $pet_deposit_amount Additional pet deposit required
 * @property int $mandatory_insurance Whether tenant insurance is required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement whereMandatoryInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement wherePetDepositAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement whereRequiredMinCreditScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyRequirement whereUpdatedAt($value)
 */
	class PropertyRequirement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $property_id
 * @property string $application_code Unique application code for tracking
 * @property string $status Application status
 * @property string|null $submitted_at Time when the application was submitted
 * @property int|null $reviewed_by
 * @property string|null $reviewed_at Time when the application was reviewed
 * @property string|null $notes Administrative notes or remarks
 * @property int $fair_housing_acknowledged Whether applicant acknowledged Fair Housing policy
 * @property string|null $risk_score System-generated risk score (0-100)
 * @property int $auto_approval Whether system auto-approved this application
 * @property int $purge_after_months Data retention period in months
 * @property string|null $last_accessed_at Last time the application was accessed (privacy auditing)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \App\Models\Applicant|null $applicant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Applicant> $applicants
 * @property-read int|null $applicants_count
 * @property-read \App\Models\Consent|null $consent
 * @property-read \App\Models\EmploymentDetail|null $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Property|null $property
 * @property-read \App\Models\User|null $reviewer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereApplicationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereAutoApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereFairHousingAcknowledged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereLastAccessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication wherePurgeAfterMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereRiskScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalApplication whereUpdatedAt($value)
 */
	class RentalApplication extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $rental_id Auto-incremented rental info ID
 * @property int|null $property_id
 * @property string $availability_status Current availability status
 * @property string $monthly_rent Advertised monthly rent
 * @property string|null $security_deposit Required security deposit
 * @property string $lease_term_type Type of lease term
 * @property int|null $min_lease_term Minimum lease duration in months
 * @property string|null $available_date Expected available move-in date
 * @property string|null $utilities_included Included utilities
 * @property string $pet_policy Pet policy for this rental
 * @property string|null $pet_fee Additional pet-related fee if any
 * @property \Illuminate\Support\Carbon $created_at Record creation time
 * @property \Illuminate\Support\Carbon $updated_at Record update time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereAvailabilityStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereAvailableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereLeaseTermType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereMinLeaseTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereMonthlyRent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo wherePetFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo wherePetPolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereRentalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereSecurityDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereUtilitiesIncluded($value)
 */
	class RentalInfo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $tenant_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string|null $emergency_contact
 * @property string|null $date_of_birth
 * @property string|null $occupation
 * @property int|null $credit_score
 * @property string|null $notes
 * @property int|null $is_active Soft delete flag
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at When record was soft deleted
 * @property int|null $deleted_by User who performed soft delete
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lease> $leases
 * @property-read int|null $leases_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreditScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereEmergencyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant withoutTrashed()
 */
	class Tenant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $avatar
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Property> $properties
 * @property-read int|null $properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

