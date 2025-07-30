<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $property_id
 * @property string $property_code 业务编码
 * @property string|null $property_name Marketing name for the property
 * @property string $property_type Type of property
 * @property string $ownership_type Ownership structure
 * @property int|null $year_built Year the property was constructed
 * @property string $address_street Street address
 * @property string $address_city City
 * @property string $address_province Province (AB, BC, ON, etc.)
 * @property string $address_postal_code Postal code
 * @property string|null $latitude Geolocation latitude
 * @property string|null $longitude Geolocation longitude
 * @property int|null $total_floors Total number of floors (for buildings)
 * @property string|null $description English description for listings
 * @property bool|null $is_active Soft delete flag
 * @property \Illuminate\Support\Carbon $created_at Record creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last update timestamp
 * @property string|null $deleted_at When record was soft deleted
 * @property int|null $deleted_by User who performed soft delete
 * @property-read \App\Models\Amenity|null $amenity
 * @property-read \App\Models\ComplianceInfo|null $complianceInfo
 * @property-read \App\Models\PropertyFeature|null $feature
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\FinancialInfo|null $financialInfo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lease> $leases
 * @property-read int|null $leases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Marketing> $marketing
 * @property-read int|null $marketing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Owner> $owners
 * @property-read int|null $owners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PropertyOwnership> $ownerships
 * @property-read int|null $ownerships_count
 * @property-read \App\Models\RentalInfo|null $rentalInfo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereAddressStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereOwnershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereTotalFloors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Property whereYearBuilt($value)
 * @mixin \Eloquent
 */
class Property extends Model
{
    use SoftDeletes;
    
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

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function owners()
    {
        return $this->belongsToMany(Owner::class, 'PropertyOwnership', 'property_id', 'owner_id')
            ->withPivot('ownership_percentage', 'start_date', 'end_date')
            ->withTimestamps()
            ->whereNull('owners.deleted_at');
    }

    // 所有关联记录（用于管理 ownership）
    public function ownerships()
    {
        return $this->hasMany(PropertyOwnership::class, 'property_id', 'property_id');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'property_id', 'property_id');
    }

    // public function ownership()
    // {
    //     return $this->hasMany(PropertyOwnership::class, 'property_id');
    // }    
}
