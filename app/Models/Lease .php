<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lease extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'lease_id'; // 自定义主键

    protected $fillable = [
        'lease_number',
        'lease_group_id',
        'version_number',
        'property_id',
        'tenant_id',
        'lease_type',
        'start_date',
        'end_date',
        'monthly_rent',
        'rent_due_day',
        'late_fee_amount',
        'late_fee_grace_days',
        'nsf_fee',
        'security_deposit',
        'furniture_deposit',
        'pet_deposit',
        'utilities_included',
        'pets_allowed',
        'smoking_allowed',
        'subletting_allowed',
        'tenant_insurance_required',
        'minimum_coverage_amount',
        'status',
        'furnished',
        'cleaning_fee',
        'insurance_required',
        'termination_policy',
        'parking_info',
        'storage_info',
        'strata_acknowledged',
        'form_k_signed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'rent_due_day' => 'integer',
        'late_fee_amount' => 'decimal:2',
        'late_fee_grace_days' => 'integer',
        'nsf_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'furniture_deposit' => 'decimal:2',
        'pet_deposit' => 'decimal:2',
        'minimum_coverage_amount' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'pets_allowed' => 'boolean',
        'smoking_allowed' => 'boolean',
        'subletting_allowed' => 'boolean',
        'tenant_insurance_required' => 'boolean',
        'insurance_required' => 'boolean',
        'furnished' => 'boolean',
        'strata_acknowledged' => 'boolean',
        'form_k_signed' => 'boolean',
    ];

    // 🔗 房源
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // 🔗 租客
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // 🔗 附加费用
    public function feeStructure()
    {
        return $this->hasMany(LeaseFeeStructure::class, 'lease_id');
    }

    // 🔗 附件（多态）
    public function attachments()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    // 🔗 检查记录
    public function inspections()
    {
        return $this->hasMany(LeaseInspection::class);
    }

    // 🔗 违规记录
    public function violations()
    {
        return $this->hasMany(LeaseViolation::class);
    }

    // 🔗 付款记录
    public function payments()
    {
        return $this->hasMany(LeasePayment::class);
    }

    
}
