<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceInfo extends Model
{
    protected $table = 'ComplianceInfo';
    protected $primaryKey = 'compliance_id';
    protected $fillable = [
        'property_id', 'property_tax_id', 'rental_license_number', 'insurance_policy_number',
        'fire_safety_compliance', 'accessibility_compliance', 'last_inspection_date'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
