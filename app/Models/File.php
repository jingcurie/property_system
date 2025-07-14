<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = [
        'title',
        'filename',
        'path',
        'mime_type',
        'size',
        'disk',
        'fileable_type',
        'fileable_id',
        'tag',
        'description',
        'is_private',
        'uploaded_by',
    ];

    /**
     * 多态关联：文件属于某个模块（如租赁申请）
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 上传者
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
