<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'owners';

    protected $primaryKey = 'owner_id';

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'emergency_contact',
        'address',
        'tax_id',
        'notes',
        'is_active',
        'deleted_at',
        'deleted_by',
    ];

    // 可选：组合全名
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_ownership', 'owner_id', 'property_id')
            ->withPivot('ownership_percentage', 'start_date', 'end_date')
            ->withTimestamps();
    }

    public function ownerships()
    {
        return $this->hasMany(PropertyOwnership::class, 'owner_id', 'owner_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
