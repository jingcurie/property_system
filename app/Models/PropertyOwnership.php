<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $ownership_id Auto-incremented ownership ID
 * @property int|null $property_id
 * @property int $owner_id Reference to owner
 * @property string|null $ownership_percentage Percentage ownership
 * @property int $is_primary
 * @property string|null $start_date When ownership began
 * @property string|null $end_date When ownership ended
 * @property \Illuminate\Support\Carbon $created_at Record creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property-read \App\Models\Owner $owner
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereOwnershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereOwnershipPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyOwnership whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PropertyOwnership extends Model
{
    protected $table = 'PropertyOwnership';

    protected $primaryKey = 'ownership_id';

    protected $fillable = [
        'property_id',
        'owner_id',
        'ownership_percentage',
        'start_date',
        'end_date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }

    public function owner()
    {
       return $this->belongsTo(\App\Models\Owner::class, 'owner_id', 'owner_id')
                ->whereNull('deleted_at');
    }
}
