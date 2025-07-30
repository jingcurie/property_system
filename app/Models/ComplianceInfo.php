<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $compliance_id Auto-incremented compliance record ID
 * @property int|null $property_id
 * @property string|null $property_tax_id Property tax ID number
 * @property string|null $rental_license_number Government-issued rental license number
 * @property string|null $insurance_policy_number Insurance policy covering the property
 * @property int|null $fire_safety_compliance Passed fire safety inspection
 * @property int|null $accessibility_compliance Compliant with accessibility regulations
 * @property string|null $last_inspection_date Date of last official or third-party inspection
 * @property \Illuminate\Support\Carbon $created_at Record creation time
 * @property \Illuminate\Support\Carbon $updated_at Record last updated time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereAccessibilityCompliance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereComplianceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereFireSafetyCompliance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereInsurancePolicyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereLastInspectionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo wherePropertyTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereRentalLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComplianceInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ComplianceInfo extends Model
{
    protected $table = 'ComplianceInfo';

    protected $primaryKey = 'compliance_id';

    protected $fillable = [
        'property_id', 'property_tax_id', 'rental_license_number', 'insurance_policy_number',
        'fire_safety_compliance', 'accessibility_compliance', 'last_inspection_date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
