<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $tenant_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string|null $emergency_contact
 * @property string|null $date_of_birth
 * @property string|null $occupation
 * @property int|null $credit_score
 * @property string|null $notes
 * @property int|null $is_active Soft delete flag
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at When record was soft deleted
 * @property int|null $deleted_by User who performed soft delete
 * @property-read mixed $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lease> $leases
 * @property-read int|null $leases_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreditScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereEmergencyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant withoutTrashed()
 * @mixin \Eloquent
 */
class Tenant extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tenant_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'emergency_contact',
        'date_of_birth',
        'occupation',
        'credit_score',
        'notes',
        'is_active',
        'deleted_by',
    ];

    protected $dates = [
        'date_of_birth',
        'deleted_at',
    ];

    // 全名访问器
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // 与租约的关联（一个租客可以有多个租约，一个租约可以多个租客）
    public function leases()
    {
        return $this->belongsToMany(Lease::class, 'lease_tenant', 'tenant_id', 'lease_id')
                    ->withPivot('is_primary', 'share_percentage', 'deleted_at', 'deleted_by')
                    ->withTimestamps()
                    ->wherePivotNull('deleted_at');
    }
}
