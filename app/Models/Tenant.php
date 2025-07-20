<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    // 与租约的关联（一个租客可以有多个租约）
    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id', 'tenant_id');
    }
}
