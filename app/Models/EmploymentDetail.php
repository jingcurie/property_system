<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @mixin \Eloquent
 */
class EmploymentDetail extends Model
{
    protected $fillable = [
        'applicant_id', 'employer_name', 'job_title', 'monthly_income',
        'income_proof_files', 'other_income_source', 'income_verified_by', 'verification_date'
    ];

    protected $casts = [
        'income_proof_files' => 'array',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }
}
