<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $feature_id Auto-incremented feature ID
 * @property int|null $property_id
 * @property int $bedrooms Number of bedrooms
 * @property string $bathrooms Number of bathrooms (0.5 for half baths)
 * @property int|null $square_footage Total area in square feet
 * @property int|null $parking_spaces Number of parking spaces included
 * @property string|null $parking_type Type of parking available
 * @property string|null $heating_type Heating system type (forced air, radiant, etc.)
 * @property string|null $cooling_type Cooling system type (central AC, window units, etc.)
 * @property int|null $furnished Whether the unit comes furnished
 * @property string $laundry
 * @property int|null $is_active Soft delete flag
 * @property string|null $deleted_at When record was soft deleted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereBedrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereCoolingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereFeatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereFurnished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereHeatingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereLaundry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereParkingSpaces($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereParkingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereSquareFootage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyFeature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
