<?php

// app/Models/PropertyMedia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $media_id Auto-incremented media ID
 * @property int|null $property_id
 * @property string $media_type Media type
 * @property string $file_path Path to media file
 * @property string|null $file_type MIME type
 * @property int|null $is_cover Whether this media is the cover
 * @property int|null $uploaded_by User ID who uploaded
 * @property \Illuminate\Support\Carbon $created_at Upload timestamp
 * @property string $upload_at Upload timestamp
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $sort_order
 * @property-read \App\Models\Property|null $property
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereIsCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereMediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereUploadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PropertyMedia whereUploadedBy($value)
 * @mixin \Eloquent
 */
class PropertyMedia extends Model
{
    protected $table = 'PropertyMedia';

    protected $primaryKey = 'media_id';

    protected $fillable = [
        'property_id', 'media_type', 'file_path', 'file_type',
        'is_cover', 'uploaded_by', 'description',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
