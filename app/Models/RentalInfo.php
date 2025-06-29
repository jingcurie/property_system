<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalInfo extends Model
{
    protected $table = 'RentalInfo';
    protected $primaryKey = 'rental_id';
    protected $fillable = [
        'property_id', 'availability_status', 'monthly_rent', 'security_deposit',
        'lease_term_type', 'min_lease_term', 'available_date', 'utilities_included',
        'pet_policy', 'pet_fee'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}

