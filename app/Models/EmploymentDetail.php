<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
