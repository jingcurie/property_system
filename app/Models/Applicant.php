<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
 * @mixin \Eloquent
 */
class Applicant extends Model
{
    protected $fillable = [
        'rental_application_id',
        'full_name',
        'email',
        'phone',
        'date_of_birth',
        'government_id_type',
        'ssn_last4',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'renters_insurance_provider',
        'policy_number',
        'coverage_amount',
        'ip_address',
        'device_fingerprint',
        'previous_application_id'
    ];

    public function rentalApplication(): BelongsTo
    {
        return $this->belongsTo(RentalApplication::class);
    }

    public function previousApplication(): BelongsTo
    {
        return $this->belongsTo(RentalApplication::class, 'previous_application_id');
    }

    public function employmentDetail(): HasOne
    {
        return $this->hasOne(EmploymentDetail::class);
    }
}
