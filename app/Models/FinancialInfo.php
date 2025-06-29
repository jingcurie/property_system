<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialInfo extends Model
{
    protected $table = 'FinancialInfo';
    protected $primaryKey = 'financial_id';
    protected $fillable = [
        'property_id', 'management_fee_percentage', 'annual_property_tax',
        'hst_included', 'maintenance_fund'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
