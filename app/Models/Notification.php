<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    // 表名
    protected $table = 'notifications';

    // 主键
    protected $primaryKey = 'notification_id';

    // 主键是否自增
    public $incrementing = true;

    // 主键类型
    protected $keyType = 'int';

    // 启用时间戳
    public $timestamps = true;

    // 可批量赋值的字段
    protected $fillable = [
        'user_id',
        'type',
        'priority',
        'title',
        'content',
        'data',
        'action_url',
        'is_read',
        'expires_at',
    ];

    // 类型转换
    protected $casts = [
        'priority'   => 'integer',
        'is_read'    => 'boolean',
        'data'       => 'array',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 获取关联的用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 访问器：兼容 $notification->message
     */
    public function getMessageAttribute()
    {
        return $this->content;
    }

    /**
     * 是否过期
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}

