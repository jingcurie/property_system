<?php

namespace App\Models\DataDictionary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DictItem extends Model
{
    protected $table = 'data_dictionary_items';
    
    protected $fillable = [
        'group_id',
        'code', 
        'value',
        'sort_order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime'
    ];
    
    public $timestamps = false; // 只有created_at字段
    
    /**
     * 关联分组
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(DictGroup::class, 'group_id');
    }
    
    /**
     * 关联翻译
     */
    public function translations(): HasMany
    {
        return $this->hasMany(DictTranslation::class, 'item_id');
    }
    
    /**
     * 获取指定语言的翻译
     */
    public function translation(string $language = 'zh-CN'): ?DictTranslation
    {
        return $this->translations()
            ->where('language', $language)
            ->first();
    }
    
    /**
     * 获取标签（带降级处理）
     */
    public function getLabel(string $language = 'zh-CN'): string
    {
        // 1. 尝试指定语言
        $translation = $this->translation($language);
        if ($translation) {
            return $translation->label;
        }
        
        // 2. 降级到中文
        if ($language !== 'zh-CN') {
            $translation = $this->translation('zh-CN');
            if ($translation) {
                return $translation->label;
            }
        }
        
        // 3. 降级到英文
        if ($language !== 'en-US') {
            $translation = $this->translation('en-US');
            if ($translation) {
                return $translation->label;
            }
        }
        
        // 4. 返回原值
        return $this->value;
    }
    
    /**
     * 获取所有语言的翻译
     */
    public function getAllTranslations(): array
    {
        return $this->translations()
            ->get()
            ->pluck('label', 'language')
            ->toArray();
    }
    
    /**
     * 根据分组代码和值查找选项
     */
    public static function findByGroupAndValue(string $groupCode, string $value): ?self
    {
        return static::whereHas('group', function($query) use ($groupCode) {
                $query->where('code', $groupCode)
                      ->where('is_active', true);
            })
            ->where('value', $value)
            ->where('is_active', true)
            ->first();
    }
}