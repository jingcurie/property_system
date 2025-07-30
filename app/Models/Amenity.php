<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $amenity_id Auto-incremented amenity ID
 * @property int|null $property_id
 * @property int|null $has_gym Whether the property has a gym
 * @property int|null $has_pool Whether the property has a swimming pool
 * @property int|null $has_balcony Whether the property has a balcony
 * @property int|null $has_elevator Whether the building has an elevator
 * @property int|null $has_dishwasher Whether the unit includes a dishwasher
 * @property int|null $has_fridge Whether the unit includes a fridge
 * @property int|null $has_stove Whether the unit includes a stove
 * @property int|null $has_microwave Whether the unit includes a microwave
 * @property int|null $has_air_conditioning Whether the unit has air conditioning
 * @property string $created_at Record creation time
 * @property string $updated_at Record last update time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereAmenityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasAirConditioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasBalcony($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasDishwasher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasElevator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasFridge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasGym($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasMicrowave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereHasStove($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Amenity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
