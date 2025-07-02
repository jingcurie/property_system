<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $table = 'Amenities';

    public $timestamps = false;

    protected $primaryKey = 'amenity_id';

    protected $fillable = [
        'property_id', 'has_gym', 'has_pool', 'has_balcony', 'has_elevator',
        'has_dishwasher', 'has_fridge', 'has_stove', 'has_microwave', 'has_air_conditioning',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
