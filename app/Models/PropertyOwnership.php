<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyOwnership extends Model
{
    protected $table = 'PropertyOwnership';
    protected $primaryKey = 'ownership_id';
    protected $fillable = [
        'property_id',
        'owner_id',
        'ownership_percentage',
        'start_date',
        'end_date'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }

    public function owner()
    {
        return $this->belongsTo(\App\Models\Owner::class, 'owner_id', 'owner_id');
    }
}
