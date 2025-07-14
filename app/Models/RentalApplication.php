<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalApplication extends Model
{
    // protected $fillable = [
    //     'property_id', 'application_code', 'status', 'submitted_at', 'reviewed_by',
    //     'reviewed_at', 'notes', 'fair_housing_acknowledged', 'risk_score',
    //     'auto_approval', 'purge_after_months', 'last_accessed_at'
    // ];
    protected $fillable = [
        'property_id',
        'application_code',
        'fair_housing_acknowledged',
        'submitted_at',
        'last_accessed_at',
    ];

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function applicant()
    {
        return $this->hasOne(Applicant::class, 'rental_application_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }


    // Employment Detail
    public function employment()
    {
        return $this->hasOneThrough(
            EmploymentDetail::class,
            Applicant::class,
            'rental_application_id', // applicant 表中外键指向 rental_application
            'applicant_id',           // employment 表中外键指向 applicant
            'id',                     // rental_application 主键
            'id'                      // applicant 主键
        );
    }


    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
