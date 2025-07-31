<?php

namespace App\Models\DataDictionary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DictGroup extends Model
{
    protected $table = 'data_dictionary_groups';
    
    protected $fillable = [
        'code',
        'description',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];
    
    public $timestamps = false; // 只有created_at字段
    
    /**
     * 获取分组下的所有选项
     */
    public function items(): HasMany
    {
        return $this->hasMany(DictItem::class, 'group_id')
            ->orderBy('sort_order');
    }
    
    /**
     * 获取分组下的激活选项
     */
    public function activeItems(): HasMany
    {
        return $this->items()->where('is_active', true);
    }
    
    /**
     * 根据代码查找分组
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)
            ->where('is_active', true)
            ->first();
    }
    
    /**
     * 获取分组选项（带翻译）
     */
    public function getOptionsWithTranslation(string $language = 'zh-CN'): array
    {
        return $this->activeItems()
            ->with(['translations' => function($query) use ($language) {
                $query->where('language', $language);
            }])
            ->get()
            ->mapWithKeys(function($item) {
                $translation = $item->translations->first();
                return [
                    $item->value => $translation ? $translation->label : $item->value
                ];
            })
            ->toArray();
    }
}