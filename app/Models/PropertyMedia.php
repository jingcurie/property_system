<?php

// app/Models/PropertyMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    protected $table = 'PropertyMedia';
    protected $primaryKey = 'media_id';
    protected $fillable = [
        'property_id', 'media_type', 'file_path', 'file_type',
        'is_cover', 'uploaded_by', 'description'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }
}
