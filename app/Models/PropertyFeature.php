<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFeature extends Model
{
    protected $table = 'PropertyFeatures';

    protected $primaryKey = 'feature_id';

    protected $fillable = [
        'property_id', 'bedrooms', 'bathrooms', 'square_footage',
        'parking_spaces', 'parking_type', 'heating_type',
        'cooling_type', 'furnished', 'laundry', 'is_active', 'deleted_at',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
