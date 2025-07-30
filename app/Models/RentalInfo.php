<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $rental_id Auto-incremented rental info ID
 * @property int|null $property_id
 * @property string $availability_status Current availability status
 * @property string $monthly_rent Advertised monthly rent
 * @property string|null $security_deposit Required security deposit
 * @property string $lease_term_type Type of lease term
 * @property int|null $min_lease_term Minimum lease duration in months
 * @property string|null $available_date Expected available move-in date
 * @property string|null $utilities_included Included utilities
 * @property string $pet_policy Pet policy for this rental
 * @property string|null $pet_fee Additional pet-related fee if any
 * @property \Illuminate\Support\Carbon $created_at Record creation time
 * @property \Illuminate\Support\Carbon $updated_at Record update time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereAvailabilityStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereAvailableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereLeaseTermType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereMinLeaseTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereMonthlyRent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo wherePetFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo wherePetPolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereRentalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereSecurityDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalInfo whereUtilitiesIncluded($value)
 * @mixin \Eloquent
 */
class RentalInfo extends Model
{
    protected $table = 'RentalInfo';

    protected $primaryKey = 'rental_id';

    protected $fillable = [
        'property_id', 'availability_status', 'monthly_rent', 'security_deposit',
        'lease_term_type', 'min_lease_term', 'available_date', 'utilities_included',
        'pet_policy', 'pet_fee',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
