<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
