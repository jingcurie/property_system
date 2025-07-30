<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $financial_id Auto-incremented financial info ID
 * @property int|null $property_id
 * @property string|null $management_fee_percentage Management fee rate in percentage (e.g. 8.50)
 * @property string|null $annual_property_tax Yearly property tax amount
 * @property int|null $hst_included Is HST (sales tax) included in rent?
 * @property string|null $maintenance_fund Reserve fund for ongoing maintenance
 * @property \Illuminate\Support\Carbon $created_at Record creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last modified timestamp
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereAnnualPropertyTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereFinancialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereHstIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereMaintenanceFund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereManagementFeePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FinancialInfo extends Model
{
    protected $table = 'FinancialInfo';

    protected $primaryKey = 'financial_id';

    protected $fillable = [
        'property_id', 'management_fee_percentage', 'annual_property_tax',
        'hst_included', 'maintenance_fund',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
