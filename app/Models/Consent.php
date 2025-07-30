<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @mixin \Eloquent
 */
class Consent extends Model
{
    protected $fillable = [
        'rental_application_id', 'credit_check_consent', 'background_check_consent',
        'signed_at', 'esignature_provider', 'esignature_id'
    ];

    public function rentalApplication(): BelongsTo
    {
        return $this->belongsTo(RentalApplication::class);
    }
}
