<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @mixin \Eloquent
 */
class PropertyRequirement extends Model
{
    protected $fillable = [
        'property_id', 'required_min_credit_score', 'pet_deposit_amount', 'mandatory_insurance'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
