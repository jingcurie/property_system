<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $marketing_id Auto-incremented marketing record ID
 * @property int|null $property_id
 * @property string $platform Marketing platform name
 * @property string|null $listing_url External link to the listing on the platform
 * @property string|null $listing_date Date the property was listed on this platform
 * @property int|null $is_active Whether the listing is currently active
 * @property string|null $seo_keywords Optional SEO keywords to enhance property searchability
 * @property string|null $description English description for platform or SEO use
 * @property \Illuminate\Support\Carbon $created_at Record creation time
 * @property \Illuminate\Support\Carbon $updated_at Record last update time
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereListingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereListingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereMarketingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereSeoKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Marketing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
