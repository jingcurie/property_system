<?php

namespace App\Models\DataDictionary;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictTranslation extends Model
{
    protected $table = 'data_dictionary_translations';
    
    protected $fillable = [
        'item_id',
        'language', 
        'label'
    ];
    
    public $timestamps = false; // 无时间戳字段
    
    /**
     * 关联选项
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(DictItem::class, 'item_id');
    }
    
    /**
     * 获取支持的语言列表
     */
    public static function getSupportedLanguages(): array
    {
        return [
            'zh-CN' => '简体中文',
            'en-US' => 'English'
        ];
    }
    
    /**
     * 批量创建或更新翻译
     */
    public static function updateTranslations(int $itemId, array $translations): void
    {
        foreach ($translations as $language => $label) {
            if (!empty($label)) {
                static::updateOrCreate(
                    [
                        'item_id' => $itemId,
                        'language' => $language,
                    ],
                    [
                        'label' => $label,
                    ]
                );
            }
        }
    }
}