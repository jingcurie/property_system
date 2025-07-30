<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @mixin \Eloquent
 */
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
