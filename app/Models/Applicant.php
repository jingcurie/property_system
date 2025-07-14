<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
