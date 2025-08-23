<?php
//----------------用户文件系统-------------------
if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes <= 0) return '0 B';

        $power = floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        $bytes /= pow(1024, $power);

        return round($bytes, $precision) . ' ' . $units[$power];
    }
}

if (!function_exists('getIconByType')) {
    function getIconByType($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $map = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
            'pdf' => ['pdf'],
            'word' => ['doc', 'docx'],
            'excel' => ['xls', 'xlsx', 'csv'],
            'ppt' => ['ppt', 'pptx'],
            'text' => ['txt', 'md'],
            'zip' => ['zip', 'rar', '7z'],
            'play' => ['mp4', 'avi', 'mov', 'webm'],
            'audio' => ['mp3', 'wav', 'aac'],
            'code' => ['js', 'php', 'py', 'html', 'css', 'json'],
        ];

        foreach ($map as $type => $exts) {
            if (in_array($ext, $exts)) {
                return "bi-file-earmark-$type";
            }
        }

        return 'bi-file-earmark'; // 默认图标
    }
}


//-----------用于搜索查询-----------------
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

if (!function_exists('applyKeywordSearch')) {
    function applyKeywordSearch(Builder $query, Request $request): Builder
    {
        $searchFieldDefs = json_decode($request->input('searchKeywordFields', '[]'), true);
        $keyword = $request->input('keyword');

        if ($keyword && !empty($searchFieldDefs)) {
            $query->where(function ($q) use ($keyword, $searchFieldDefs) {
                foreach ($searchFieldDefs as $def) {
                    $relation = $def['relation'] ?? null;
                    $column = $def['column'] ?? null;
                    if (!$column) continue;

                    $relation
                        ? $q->orWhereHas($relation, fn($subQ) => $subQ->where($column, 'like', "%{$keyword}%"))
                        : $q->orWhere($column, 'like', "%{$keyword}%");
                }
            });
        }

        return $query;
    }
}

if (!function_exists('applyFilters')) {
    function applyFilters(Builder $query, Request $request): Builder
    {
        if (!$request->filled('filters')) return $query;
        $filters = $request->input('filters', []);
        $filterFields = json_decode($request->input('filterFields', '[]'), true) ?? [];

        foreach ($filters as $id => $filterKey) {
            // 跳过 module，因为它不是字段，在回收站时快筛选择是模块，不是字段
            if ($filterKey === 'module') {
                continue;
            }

            $fieldDef = collect($filterFields)->firstWhere('key', $filterKey);
            $value = $request->input("filter_values.$filterKey");

            if (!$fieldDef) continue;

            $type = $fieldDef['type'] ?? 'text';
            $column = $fieldDef['column'] ?? $filterKey;
            $relation = $fieldDef['relation'] ?? null;

            switch ($type) {
                case 'select':
                    if (is_array($value)) {
                        if (empty($value)) {
                            // 用户全不选 → 返回无结果
                            $query->whereRaw('1 = 0');
                        } else {
                            $relation
                                ? $query->whereHas($relation, fn($q) => $q->whereIn($column, $value))
                                : $query->whereIn($column, $value);
                        }
                    } else {
                        $relation
                            ? $query->whereHas($relation, fn($q) => $q->where($column, $value))
                            : $query->where($column, $value);
                    }
                    break;

                case 'number_range':
                    $min = $value['min'] ?? null;
                    $max = $value['max'] ?? null;
                    if ($min !== null && $max !== null) {
                        $relation
                            ? $query->whereHas($relation, fn($q) => $q->whereBetween($column, [$min, $max]))
                            : $query->whereBetween($column, [$min, $max]);
                    } elseif ($min !== null) {
                        $relation
                            ? $query->whereHas($relation, fn($q) => $q->where($column, '>=', $min))
                            : $query->where($column, '>=', $min);
                    } elseif ($max !== null) {
                        $relation
                            ? $query->whereHas($relation, fn($q) => $q->where($column, '<=', $max))
                            : $query->where($column, '<=', $max);
                    }
                    break;

                case 'date_range':
                    $date_from = $value['start'] ?? null;
                    $date_to   = $value['end'] ?? null;

                    if ($date_from) {
                        $date_from = \Carbon\Carbon::parse($date_from)->startOfDay(); //从一天最开始开始，包括00:00:00
                    }
                    if ($date_to) {
                        $date_to = \Carbon\Carbon::parse($date_to)->endOfDay();
                    }

                    if ($date_from !== null && $date_to !== null) {
                        $callback = fn($q) => $q->whereBetween($column, [$date_from, $date_to]);
                        $relation
                            ? $query->whereHas($relation, $callback)
                            : $query->whereBetween($column, [$date_from, $date_to]);
                    } elseif ($date_from !== null) {
                        $callback = fn($q) => $q->where($column, '>=', $date_from);
                        $relation
                            ? $query->whereHas($relation, $callback)
                            : $query->where($column, '>=', $date_from);
                    } elseif ($date_to !== null) {
                        $callback = fn($q) => $q->where($column, '<=', $date_to);
                        $relation
                            ? $query->whereHas($relation, $callback)
                            : $query->where($column, '<=', $date_to);
                    }
                    break;


                case 'text':
                default:
                    if ($value !== null && $value !== '') {
                        $relation
                            ? $query->whereHas($relation, fn($q) => $q->where($column, 'like', "%$value%"))
                            : $query->where($column, 'like', "%$value%");
                    }
                    break;
            }
        }

        return $query;
    }
}

//排序代码若干functions
if (!function_exists('applySorting')) {
    /**
     * 通用排序方法 - 支持多表关联
     */
    function applySorting($query, $request)
    {
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');
        $sortableFields = collect(json_decode($request->input('sortableFields', '{}'), true));

        if (!$sort || !$sortableFields->has($sort)) {
            return $query->latest();
        }

        $fieldInfo = $sortableFields[$sort];
        $relation = $fieldInfo['relation'] ?? null;
        $column = $fieldInfo['column'];

        if ($relation) {
            // 关联表排序 - 支持多层关联
            return applyRelationSorting($query, $relation, $column, $direction);
        } else {
            // 主表字段排序
            return $query->orderBy($column, $direction);
        }
    }
}

if (!function_exists('applyRelationSorting')) {
    /**
     * 关联表排序 - 支持任意深度的关联
     */
    function applyRelationSorting($query, $relationPath, $column, $direction)
    {
        // 构建子查询
        $subQuery = buildRelationSubQuery($query, $relationPath, $column);

        return $query->orderBy($subQuery, $direction);
    }
}

if (!function_exists('buildRelationSubQuery')) {
    /**
     * 构建关联子查询
     */
    function buildRelationSubQuery($query, $relationPath, $column)
    {
        $model = $query->getModel();
        $mainTable = $model->getTable();
        $primaryKey = $model->getKeyName();

        // 分解关联路径：ownership.owner.profile
        $relations = explode('.', $relationPath);

        // 从主模型开始，逐步构建关联链
        $currentModel = $model;
        $joins = [];
        $currentTable = $mainTable;

        foreach ($relations as $relationName) {
            // 获取关联实例
            $relationInstance = $currentModel->$relationName();
            $relatedModel = $relationInstance->getRelated();
            $relatedTable = $relatedModel->getTable();

            // 根据关联类型获取正确的键名
            $foreignKey = $relationInstance->getForeignKeyName();

            // 根据关联类型获取本地键名
            if (method_exists($relationInstance, 'getOwnerKeyName')) {
                // BelongsTo 关联
                $ownerKey = $relationInstance->getOwnerKeyName();
            } elseif (method_exists($relationInstance, 'getLocalKeyName')) {
                // HasOne/HasMany 关联
                $ownerKey = $relationInstance->getLocalKeyName();
            } elseif (method_exists($relationInstance, 'getParentKeyName')) {
                // 某些关联类型
                $ownerKey = $relationInstance->getParentKeyName();
            } else {
                // 默认使用主键
                $ownerKey = $currentModel->getKeyName();
            }

            // 构建JOIN信息
            $joins[] = [
                'table' => $relatedTable,
                'foreign_key' => basename($foreignKey), // 只要列名，不要表名
                'owner_key' => $ownerKey,
                'current_table' => $currentTable,
            ];

            // 为下一次循环准备
            $currentModel = $relatedModel;
            $currentTable = $relatedTable;
        }

        // 构建子查询
        $firstJoin = $joins[0];
        $subQuery = \DB::table($firstJoin['table']);

        // 逐步添加JOIN
        for ($i = 1; $i < count($joins); $i++) {
            $prevJoin = $joins[$i - 1];
            $currentJoin = $joins[$i];

            $subQuery->join(
                $currentJoin['table'],
                $prevJoin['table'] . '.' . $prevJoin['owner_key'],
                '=',
                $currentJoin['table'] . '.' . $currentJoin['foreign_key']
            );
        }

        // 选择最终字段并添加WHERE条件
        $finalTable = end($joins)['table'];

        // 修复whereColumn的用法 - 传两个参数
        $subQuery->select("$finalTable.$column")
            ->whereColumn(
                $firstJoin['table'] . '.' . $firstJoin['foreign_key'],
                "$mainTable.$primaryKey"
            )
            ->limit(1);

        return $subQuery;
    }
}

if (!function_exists('applyPagination')) {
    function applyPagination(Builder $query, Request $request, int $defaultPerPage = 0)
    {
        $perPage = $request->input('per_page', $defaultPerPage);

        // 如果默认值是 0 或用户选择 all，则显示所有记录
        if ($perPage === 'all' || (int)$perPage === 0) {
            $perPage = $query->count();
        }

        return $query->paginate((int) $perPage)->appends($request->all());
    }
}


//-----------------用于字典--------------------
if (!function_exists('dict')) {
    /**
     * 获取字典数据
     */
    function dict($group, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $cacheKey = "dict_{$group}_{$locale}";
        
        
        return Cache::remember($cacheKey, 3600, function () use ($group, $locale) {
            $dictGroup = \App\Models\DataDictionary\DictGroup::where('code', $group)->first();
            if (!$dictGroup) {
                return [];
            }
            
            return $dictGroup->items()
                ->with(['translations' => function ($query) use ($locale) {
                    $query->where('language', $locale);
                }])
                ->get()
                ->mapWithKeys(function ($item) use ($locale) {
                    $translation = $item->translations->first();
                    return [$item->code => $translation ? $translation->label : $item->value];
                })
                ->toArray();
        });
    }
}

if (!function_exists('dict_label')) {
    /**
     * 获取字典标签
     * 
     * @param string $groupCode 分组代码  
     * @param string $value 值
     * @param string|null $language 语言代码
     * @return string
     */
    function dict_label(string $groupCode, string $value, ?string $language = null): string
    {
        return app(App\Services\DictionaryService::class)->getLabel($groupCode, $value, $language);
    }
}

if (!function_exists('dict_colors')) {
    /**
     * 获取字典颜色映射
     */
    function dict_colors($group)
    {
        $service = app(\App\Services\DictionaryService::class);
        return $service->getBadgeMap($group);
    }
}

if (!function_exists('unified_trans')) {
    /**
     * 统一翻译函数
     * 支持多层级访问：unified_trans('common.create') 或 unified_trans('modules.property.page_title')
     */
    function unified_trans($key, $locale = null)
{
    $locale = $locale ?: app()->getLocale();
    $cacheKey = "unified_trans_{$key}_{$locale}";
    
    return Cache::remember($cacheKey, 3600, function () use ($key, $locale) {
        $translations = require lang_path("{$locale}/translations.php");
        $keys = explode('.', $key);
        $value = $translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key; // 如果找不到翻译，返回原key
            }
        }
        
        return is_string($value) ? $value : $key;
    });
}
}

if (!function_exists('ut')) {
    /**
     * 统一翻译的简写
     */
    function ut($key, $locale = null)
    {
        return unified_trans($key, $locale);
    }
}

if (!function_exists('js_trans')) {
    /**
     * JavaScript翻译函数
     * 用于在JavaScript中获取翻译
     */
    function js_trans($key, $locale = null)
    {
        return unified_trans("js.{$key}", $locale);
    }
}

if (!function_exists('get_js_translations')) {
    /**
     * 获取所有JavaScript翻译
     */
    function get_js_translations($locale = null)
{
    $locale = $locale ?: app()->getLocale();
    $cacheKey = "js_translations_{$locale}";
    
    return Cache::remember($cacheKey, 3600, function () use ($locale) {
        $translations = require lang_path("{$locale}/translations.php");
        return $translations['js'] ?? [];
    });
}
}
