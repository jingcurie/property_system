<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataDictionary\DictGroup;
use App\Models\DataDictionary\DictItem;
use App\Models\DataDictionary\DictTranslation;
use App\Services\DictionaryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DictController extends Controller
{
    public function __construct(
        protected DictionaryService $dictionaryService
    ) {}

    /**
     * 字典管理主页面
     */
    public function index(): View
    {
        $groups = DictGroup::where('is_active', true)->orderBy('code')->get();
        $languages = DictTranslation::getSupportedLanguages();
        
        return view('dict.index', compact('groups', 'languages'));
    }

    /**
     * 获取分组列表（Ajax）
     */
    public function getGroups(): JsonResponse
    {
        $groups = DictGroup::select('id', 'code', 'description', 'is_active')
            ->withCount('items')
            ->orderBy('code')
            ->get();

        return response()->json($groups);
    }

    /**
     * 获取字典项列表（Ajax）
     */
    public function getItems(Request $request): JsonResponse
    {
        $groupId = $request->get('group_id');
        
        if (!$groupId) {
            return response()->json([]);
        }

        $items = DictItem::where('group_id', $groupId)
            ->with(['translations'])
            ->orderBy('sort_order')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->code,
                    'value' => $item->value,
                    'sort_order' => $item->sort_order,
                    'is_active' => $item->is_active,
                    'translations' => $item->getAllTranslations()
                ];
            });

        return response()->json($items);
    }

    /**
     * 新增分组
     */
    public function storeGroup(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:data_dictionary_groups,code',
            'description' => 'required|string|max:200',
            'is_active' => 'boolean'
        ]);

        $group = DictGroup::create([
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => '分组创建成功',
            'group' => $group
        ]);
    }

    /**
     * 更新分组
     */
    public function updateGroup(Request $request, DictGroup $group): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:data_dictionary_groups,code,' . $group->id,
            'description' => 'required|string|max:200',
            'is_active' => 'boolean'
        ]);

        $group->update([
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        // 清除缓存
        $this->dictionaryService->clearCache($group->code);

        return response()->json([
            'success' => true,
            'message' => '分组更新成功',
            'group' => $group
        ]);
    }

    /**
     * 删除分组
     */
    public function destroyGroup(DictGroup $group): JsonResponse
    {
        if ($group->items()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => '该分组下还有字典项，无法删除'
            ], 400);
        }

        $groupCode = $group->code;
        $group->delete();

        // 清除缓存
        $this->dictionaryService->clearCache($groupCode);

        return response()->json([
            'success' => true,
            'message' => '分组删除成功'
        ]);
    }

    /**
     * 新增字典项
     */
    public function storeItem(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:data_dictionary_groups,id',
            'code' => 'required|string|max:50',
            'value' => 'required|string|max:100',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'translations' => 'array',
            'translations.*' => 'nullable|string|max:200'
        ]);

        // 检查同一分组下code是否重复
        $group = DictGroup::findOrFail($request->group_id);
        if ($group->items()->where('code', $request->code)->exists()) {
            return response()->json([
                'success' => false,
                'message' => '该分组下已存在相同代码的字典项'
            ], 400);
        }

        $sortOrder = $request->sort_order;
        if (empty($sortOrder) || $sortOrder == 0) {
            $sortOrder = $this->getNextSortOrder($request->group_id);
        } else {
            // 如果指定了序号，需要处理序号冲突
            $this->handleSortOrderConflict($request->group_id, $sortOrder);
        }

        $item = DictItem::create([
            'group_id' => $request->group_id,
            'code' => $request->code,
            'value' => $request->value,
            'sort_order' => $sortOrder,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // 创建翻译
        if ($request->translations) {
            DictTranslation::updateTranslations($item->id, $request->translations);
        }

        // 清除缓存
        $this->dictionaryService->clearCache($group->code);

        // 重新加载数据返回
        $item->load('translations');

        return response()->json([
            'success' => true,
            'message' => '字典项创建成功',
            'item' => [
                'id' => $item->id,
                'code' => $item->code,
                'value' => $item->value,
                'sort_order' => $item->sort_order,
                'is_active' => $item->is_active,
                'translations' => $item->getAllTranslations()
            ]
        ]);
    }

    /**
     * 更新字典项
     */
    public function updateItem(Request $request, DictItem $item): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'value' => 'required|string|max:100',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'translations' => 'array',
            'translations.*' => 'nullable|string|max:200'
        ]);

        // 检查同一分组下code是否重复（排除自己）
        $group = $item->group;
        if ($group->items()->where('code', $request->code)->where('id', '!=', $item->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => '该分组下已存在相同代码的字典项'
            ], 400);
        }

        $newSortOrder = $request->sort_order ?: $item->sort_order;
        
        // 如果序号发生变化，需要处理冲突
        if ($newSortOrder != $item->sort_order) {
            $this->handleSortOrderConflictForUpdate($group->id, $newSortOrder, $item->id);
        }

        $item->update([
            'code' => $request->code,
            'value' => $request->value,
            'sort_order' => $newSortOrder,
            'is_active' => $request->boolean('is_active'),
        ]);

        // 更新翻译
        if ($request->translations) {
            // 先删除旧翻译
            $item->translations()->delete();
            // 创建新翻译
            DictTranslation::updateTranslations($item->id, $request->translations);
        }

        // 清除缓存
        $this->dictionaryService->clearCache($group->code);

        // 重新加载数据返回
        $item->load('translations');

        return response()->json([
            'success' => true,
            'message' => '字典项更新成功',
            'item' => [
                'id' => $item->id,
                'code' => $item->code,
                'value' => $item->value,
                'sort_order' => $item->sort_order,
                'is_active' => $item->is_active,
                'translations' => $item->getAllTranslations()
            ]
        ]);
    }

    /**
     * 删除字典项
     */
    public function destroyItem(DictItem $item): JsonResponse
    {
        $groupCode = $item->group->code;
        $groupId = $item->group_id;
        $deletedSortOrder = $item->sort_order;
        
        // 删除翻译
        $item->translations()->delete();
        
        // 删除字典项
        $item->delete();

        // 将删除项之后的所有项目序号-1，保持连续
        DictItem::where('group_id', $groupId)
            ->where('sort_order', '>', $deletedSortOrder)
            ->decrement('sort_order');

        // 清除缓存
        $this->dictionaryService->clearCache($groupCode);

        return response()->json([
            'success' => true,
            'message' => '字典项删除成功'
        ]);
    }

    /**
     * 更新字典项排序
     */
    public function updateSort(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:data_dictionary_groups,id',
            'items' => 'required|array',
            'items.*' => 'integer|exists:data_dictionary_items,id'
        ]);

        $group = DictGroup::findOrFail($request->group_id);

        foreach ($request->items as $sortOrder => $itemId) {
            DictItem::where('id', $itemId)
                ->where('group_id', $group->id)
                ->update(['sort_order' => $sortOrder + 1]); // 从1开始的连续序号
        }

        // 清除缓存
        $this->dictionaryService->clearCache($group->code);

        return response()->json([
            'success' => true,
            'message' => '排序更新成功'
        ]);
    }

    /**
     * 获取下一个排序号
     */
    private function getNextSortOrder(int $groupId): int
    {
        return DictItem::where('group_id', $groupId)->max('sort_order') + 1;
    }

    /**
     * 处理新增时的序号冲突
     */
    private function handleSortOrderConflict(int $groupId, int $sortOrder): void
    {
        // 将指定序号及之后的所有项目序号+1
        DictItem::where('group_id', $groupId)
            ->where('sort_order', '>=', $sortOrder)
            ->increment('sort_order');
    }

    /**
     * 处理更新时的序号冲突
     */
    private function handleSortOrderConflictForUpdate(int $groupId, int $newSortOrder, int $currentItemId): void
    {
        // 将指定序号及之后的所有项目序号+1（排除当前项目）
        DictItem::where('group_id', $groupId)
            ->where('sort_order', '>=', $newSortOrder)
            ->where('id', '!=', $currentItemId)
            ->increment('sort_order');
    }
}