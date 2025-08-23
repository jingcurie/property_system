<?php

namespace App\Services;

use App\Models\DataDictionary\DictGroup;
use App\Models\DataDictionary\DictItem;
use App\Models\DataDictionary\DictTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class DictionaryService
{
    /**
     * 缓存时间（秒）
     */
    protected int $cacheTime = 1800; // 30分钟

    /**
     * 获取字典选项（下拉框用）
     */
    public function getOptions(string $groupCode, string $language = null): array
    {
        $language = $language ?: app()->getLocale();

        // Laravel简化格式 -> 字典标准格式
        $languageMap = [
            'zh' => 'zh-CN',
            'en' => 'en-US',
            'fr' => 'fr-FR',
            'ja' => 'ja-JP'
        ];

        $dictLanguage = $languageMap[$language] ?? $language;

        $cacheKey = "dict.options.{$groupCode}.{$dictLanguage}";

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($groupCode, $dictLanguage) {
            $group = DictGroup::findByCode($groupCode);
            if (!$group) {
                return [];
            }

            return $group->getOptionsWithTranslation($dictLanguage);
        });
    }

    /**
     * 获取字典标签（单个值）
     */
    public function getLabel(string $groupCode, string $value, string $language = null): string
    {
        $language = $language ?: app()->getLocale();
        $cacheKey = "dict.label.{$groupCode}.{$value}.{$language}";

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($groupCode, $value, $language) {
            $item = DictItem::findByGroupAndValue($groupCode, $value);
            if (!$item) {
                return $value; // 找不到时返回原值
            }

            return $item->getLabel($language);
        });
    }

    /**
     * 批量获取标签
     */
    public function getLabels(string $groupCode, array $values, string $language = null): array
    {
        $language = $language ?: app()->getLocale();
        $labels = [];

        foreach ($values as $value) {
            $labels[$value] = $this->getLabel($groupCode, $value, $language);
        }

        return $labels;
    }

    /**
     * 获取徽章样式映射（用于表格组件）
     */
    public function getBadgeMap(string $groupCode): array
    {
        $cacheKey = "dict.badge.{$groupCode}";

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($groupCode) {
            // 预定义的徽章样式映射
            $defaultMaps = [
                'availability_status' => [
                    'Available' => 'success',
                    'Leased' => 'secondary',
                    'Under Maintenance' => 'warning',
                    'Pending Lease' => 'info'
                ],
                'property_type' => [
                    'Apartment' => 'primary',
                    'House' => 'success',
                    'Townhouse' => 'info',
                    'Basement' => 'secondary',
                    'Condo' => 'warning',
                    'Other' => 'dark'
                ],
                'ownership_type' => [
                    'Owned' => 'success',
                    'Managed' => 'info'
                ],
                'parking_type' => [
                    'Indoor' => 'primary',
                    'Outdoor' => 'success',
                    'Garage' => 'info',
                    'None' => 'secondary'
                ],
                'lease_term_type' => [
                    'Monthly' => 'primary',
                    'Fixed Term' => 'success',
                    'Annual' => 'info'
                ],
                'pet_policy' => [
                    'Allowed' => 'success',
                    'Restricted' => 'warning',
                    'Not Allowed' => 'danger'
                ],
                'payment_status' => [
                    'Pending' => 'warning',
                    'Paid' => 'success',
                    'Failed' => 'danger',
                    'Cancelled' => 'secondary',
                    'Overdue' => 'danger',
                    'Partial' => 'info'
                ],
                'application_status' => [
                    'submitted' => 'secondary',
                    'under_review' => 'info',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ],
                'lease_status' => [
                    'Draft' => 'secondary',
                    'Active' => 'success',
                    'Expired' => 'warning',
                    'Terminated' => 'danger',
                    'Voided' => 'danger'
                ]
            ];

            return $defaultMaps[$groupCode] ?? [];
        });
    }

    /**
     * 获取分组列表
     */
    public function getGroups(): Collection
    {
        return Cache::remember('dict.groups', $this->cacheTime, function () {
            return DictGroup::where('is_active', true)
                ->orderBy('code')
                ->get();
        });
    }

    /**
     * 创建字典项
     */
    public function createItem(string $groupCode, string $code, string $value, array $translations, int $sortOrder = 0): DictItem
    {
        $group = DictGroup::findByCode($groupCode);
        if (!$group) {
            throw new \Exception("字典分组 {$groupCode} 不存在");
        }

        $item = DictItem::create([
            'group_id' => $group->id,
            'code' => $code,
            'value' => $value,
            'sort_order' => $sortOrder ?: $this->getNextSortOrder($group->id),
            'is_active' => true,
        ]);

        // 创建翻译
        DictTranslation::updateTranslations($item->id, $translations);

        // 清除缓存
        $this->clearCache($groupCode);

        return $item;
    }

    /**
     * 更新字典项
     */
    public function updateItem(int $itemId, array $data, array $translations = []): DictItem
    {
        $item = DictItem::findOrFail($itemId);
        $groupCode = $item->group->code;

        $item->update($data);

        if (!empty($translations)) {
            DictTranslation::updateTranslations($itemId, $translations);
        }

        // 清除缓存
        $this->clearCache($groupCode);

        return $item;
    }

    /**
     * 删除字典项
     */
    public function deleteItem(int $itemId): bool
    {
        $item = DictItem::findOrFail($itemId);
        $groupCode = $item->group->code;

        // 删除翻译
        $item->translations()->delete();

        // 删除选项
        $result = $item->delete();

        // 清除缓存
        $this->clearCache($groupCode);

        return $result;
    }

    /**
     * 获取下一个排序号
     */
    protected function getNextSortOrder(int $groupId): int
    {
        return DictItem::where('group_id', $groupId)->max('sort_order') + 1;
    }

    /**
     * 清除缓存
     */
    public function clearCache(string $groupCode = null): void
    {
        if ($groupCode) {
            // 清除特定分组缓存
            $languages = ['zh-CN', 'en-US'];
            foreach ($languages as $lang) {
                Cache::forget("dict.options.{$groupCode}.{$lang}");
            }
            Cache::forget("dict.badge.{$groupCode}");

            // 清除标签缓存（这里简化处理，实际可以更精确）
            Cache::flush(); // 或者使用Cache tags
        } else {
            // 清除所有字典缓存
            Cache::forget('dict.groups');
            Cache::flush();
        }
    }
}
