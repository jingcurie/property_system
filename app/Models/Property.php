<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $primaryKey = 'property_id';

    public $incrementing = false; // 主键非自增

    protected $keyType = 'string'; // 主键是字符串类型

    protected $table = 'Properties';

    protected $fillable = [
        'property_id',
        'property_name',
        'property_type',
        'ownership_type',
        'year_built',
        'address_street',
        'address_city',
        'address_province',
        'address_postal_code',
        'latitude',
        'longitude',
        'total_floors',
        'description',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 关联：1对1
    public function feature()
    {
        return $this->hasOne(PropertyFeature::class, 'property_id', 'property_id');
    }

    public function amenity()
    {
        return $this->hasOne(Amenity::class, 'property_id', 'property_id');
    }

    public function financialInfo()
    {
        return $this->hasOne(FinancialInfo::class, 'property_id', 'property_id');
    }

    public function complianceInfo()
    {
        return $this->hasOne(ComplianceInfo::class, 'property_id', 'property_id');
    }

    public function rentalInfo()
    {
        return $this->hasOne(RentalInfo::class, 'property_id', 'property_id');
    }

    // 关联：1对多
    public function media()
    {
        return $this->hasMany(PropertyMedia::class, 'property_id', 'property_id')->orderBy('sort_order');
    }

    public function marketing()
    {
        return $this->hasMany(Marketing::class, 'property_id', 'property_id');
    }

    // public function ownerships()
    // {
    //     return $this->hasMany(PropertyOwnership::class, 'property_id', 'property_id');
    // }

    public function ownership()
    {
        return $this->hasOne(PropertyOwnership::class, 'property_id', 'property_id');
    }
}
