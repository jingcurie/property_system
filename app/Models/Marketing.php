<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    protected $table = 'Marketing';

    protected $primaryKey = 'marketing_id';

    protected $fillable = [
        'property_id', 'platform', 'listing_url', 'listing_date',
        'is_active', 'seo_keywords', 'description',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
