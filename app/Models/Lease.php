<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $lease_id
 * @property string $lease_number 租赁编号，如 L2025001
 * @property string|null $lease_group_id 租赁组ID，处理续约关系
 * @property int $version_number 版本号（续约递增）
 * @property int $property_id 房产ID
 * @property string $lease_type 租赁类型
 * @property \Illuminate\Support\Carbon $start_date 开始日期
 * @property \Illuminate\Support\Carbon|null $end_date 结束日期
 * @property numeric $monthly_rent 月租金
 * @property int $rent_due_day 租金到期日
 * @property numeric $late_fee_amount 滞纳金金额
 * @property int $late_fee_grace_days 滞纳金宽限天数
 * @property numeric $nsf_fee NSF费用
 * @property numeric $security_deposit 保证金
 * @property numeric $furniture_deposit 家具押金
 * @property numeric $pet_deposit 宠物押金
 * @property string|null $utilities_included 包含的公用事业
 * @property bool $pets_allowed 是否允许宠物
 * @property bool $smoking_allowed 是否允许吸烟
 * @property bool $subletting_allowed 是否允许转租
 * @property bool $tenant_insurance_required 是否需要租户保险
 * @property numeric|null $minimum_coverage_amount 最低保险金额
 * @property string $status 租赁状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $deleted_by
 * @property bool $furnished
 * @property numeric|null $cleaning_fee
 * @property bool $insurance_required
 * @property string|null $termination_policy
 * @property string|null $parking_info
 * @property string|null $storage_info
 * @property bool $strata_acknowledged
 * @property bool $form_k_signed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeaseFeeStructure> $feeStructures
 * @property-read int|null $fee_structures_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Property $property
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenant> $tenants
 * @property-read int|null $tenants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease past()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereCleaningFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereFormKSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereFurnished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereFurnitureDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereInsuranceRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLateFeeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLateFeeGraceDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereLeaseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereMinimumCoverageAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereMonthlyRent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereNsfFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereParkingInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease wherePetDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease wherePetsAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereRentDueDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereSecurityDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereSmokingAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStorageInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereStrataAcknowledged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereSublettingAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereTenantInsuranceRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereTerminationPolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereUtilitiesIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lease whereVersionNumber($value)
 * @mixin \Eloquent
 */
class Lease extends Model
{
    protected $primaryKey = 'lease_id'; // 自定义主键

    protected $fillable = [
        'lease_number',
        'lease_group_id',
        'version_number',
        'property_id',
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
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }

    // 🔗 租客
    // 🔁 多租客支持（替换掉旧的 tenant() 方法）
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'lease_tenants', 'lease_id', 'tenant_id')
            ->withPivot('is_primary', 'share_percentage', 'deleted_at', 'deleted_by')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    // ✅ 主租客访问器（可选）
    public function primaryTenant()
    {
        return $this->tenants()->wherePivot('is_primary', 1)->first();
    }

    // ✅ 当前合同作用域
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ✅ 历史合同作用域
    public function scopePast($query)
    {
        return $query->whereIn('status', ['terminated', 'expired']);
    }


    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    // 🔗 附加费用
    public function feeStructures()
    {
        return $this->hasMany(LeaseFeeStructure::class, 'lease_id', 'lease_id');
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
