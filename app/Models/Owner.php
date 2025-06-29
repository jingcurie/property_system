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

    // 所拥有的房产（如果你以后要用）
    public function properties()
    {
        return $this->hasManyThrough(Property::class, PropertyOwnership::class, 'owner_id', 'property_id', 'owner_id', 'property_id');
    }
}
