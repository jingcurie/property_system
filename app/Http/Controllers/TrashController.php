<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\User;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\RentalApplication;

class TrashController extends Controller
{
    protected $modules = [
        'properties' => Property::class,
        'owners'     => Owner::class,
        // 'users'      => User::class,
    ];

    public function index(Request $request)
    {
        // 默认模块
        $module = $request->input('filter_values.module', 'properties');

        // 模块与模型映射
        $models = [
            'properties' => Property::class,
            'owners'     => Owner::class,
            'tenants' => Tenant::class,
            'rentalApplications'     => rentalApplication::class,
        ];

        $modelClass = $models[$module] ?? \App\Models\Property::class;

        $query = $modelClass::onlyTrashed();

        $query = applyKeywordSearch($query, $request);
        $query = applyFilters($query, $request);
        $query = applySorting($query, $request);
        $records = applyPagination($query, $request);

        // 删除人下拉
        $deletedUsers = \App\Models\User::pluck('name', 'id');
        // dd($deletedUsers);
        // 根据模块动态设置表格列
        $columns = $this->getColumnsForModule($module);

        // dd($columns);
        return view('trash.index', compact('records', 'module', 'deletedUsers', 'columns'));
    }

    private function getColumnsForModule(string $module): array
    {
        switch ($module) {
            case 'owners':
                return [
                    ['label' => 'ID', 'column' => 'owner_id', 'sortable' => true],
                    ['label' => '姓名', 'column' => 'first_name', 'type' => 'custom', 'render' => fn($item) => e(trim(($item->first_name ?? '') . ' ' . ($item->last_name ?? ''))), 'sortable' => true],
                    ['label' => '邮箱', 'column' => 'email', 'sortable' => true],
                    ['label' => '删除时间', 'column' => 'deleted_at', 'sortable' => true],
                    ['label' => '删除人', 'column' => 'deleted_by', 'type' => 'custom', 'render' => fn($item) => e(optional($item->deletedBy)->name ?? '-'), 'sortable' => true],
                ];
            case 'properties':
                return [
                    ['label' => 'ID', 'column' => 'property_id', 'sortable' => true],
                    ['label' => '房源名称', 'column' => 'property_name', 'sortable' => true],
                    ['label' => '地址', 'column' => 'address_street', 'sortable' => true],
                    ['label' => '删除时间', 'column' => 'deleted_at', 'sortable' => true],
                    ['label' => '删除人', 'columns' => 'deleted_by_user_name', 'sortable' => true, 'type' => 'custom', 'render' => fn($item) => e(optional($item->deletedBy)->name ?? '-')],
                ];
            default:
                return [
                    ['label' => 'ID', 'column' => 'property_id', 'sortable' => true],
                    ['label' => '房源名称', 'column' => 'property_name', 'sortable' => true],
                    ['label' => '地址', 'column' => 'address_street', 'sortable' => true],
                    ['label' => '删除时间', 'column' => 'deleted_at', 'sortable' => true],
                    ['label' => '删除人', 'type' => 'custom', 'render' => fn($item) => e(optional($item->deletedBy)->name ?? '-'), 'sortable' => true],
                ];
        }
    }


    public function restore(Request $request, $module, $id)
    {
        try {
            $model = $this->modules[$module] ?? null;
            if (!$model) {
                return response()->json(['message' => '模块不存在'], 404);
            }

            $record = $model::onlyTrashed()->find($id);
            if (!$record) {
                return response()->json(['message' => '记录不存在或未被删除'], 404);
            }

            $record->restore();

            activity()->performedOn($record)
                ->causedBy(auth()->user())
                ->log("Record restored from trash");

            return response()->json([
                'message' => '记录已恢复',
                'id' => $id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '恢复失败: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function forceDelete(Request $request, $module, $id)
    {
        try {
            $model = $this->modules[$module] ?? null;
            if (!$model) {
                return response()->json(['message' => '模块不存在'], 404);
            }

            $record = $model::onlyTrashed()->find($id);
            if (!$record) {
                return response()->json(['message' => '记录不存在或未被删除'], 404);
            }

            $record->forceDelete();

            activity()->performedOn($record)
                ->causedBy(auth()->user())
                ->log("Record permanently deleted");

            return response()->json([
                'message' => '记录已彻底删除',
                'id' => $id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '删除失败: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function bulkRestore($module, Request $request)
    {
        $model = $this->modules[$module] ?? null;
        if (!$model) {
            return response()->json(['error' => '无效的模块'], 404);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['error' => '请选择至少一条记录'], 422);
        }

        try {
            $primaryKey = (new $model)->getKeyName();
            $records = $model::onlyTrashed()->whereIn($primaryKey, $ids)->get();

            foreach ($records as $record) {
                $record->restore();
                activity()->performedOn($record)
                    ->causedBy(auth()->user())
                    ->log("Record restored from trash");
            }

            return response()->json(['message' => '选中记录已恢复']);
        } catch (\Throwable $e) {
            return response()->json(['error' => '恢复失败: ' . $e->getMessage()], 500);
        }
    }

    public function bulkForceDelete(Request $request, $module)
    {
        $model = $this->modules[$module] ?? null;
        if (!$model) {
            return response()->json(['error' => '无效的模块'], 404);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['error' => '请选择至少一条记录'], 422);
        }

        try {
            $primaryKey = (new $model)->getKeyName();
            $records = $model::onlyTrashed()->whereIn($primaryKey, $ids)->get();

            foreach ($records as $record) {
                $record->forceDelete();
                activity()->performedOn($record)
                    ->causedBy(auth()->user())
                    ->log("Record permanently deleted from trash");
            }

            return response()->json(['message' => '选中记录已彻底删除']);
        } catch (\Throwable $e) {
            return response()->json(['error' => '删除失败: ' . $e->getMessage()], 500);
        }
    }



    // public function bulkAction(Request $request, $module)
    // {
    //     $request->validate([
    //         'action' => 'required|in:restore,force_delete',
    //         'ids' => 'required|array',
    //     ]);

    //     $model = $this->modules[$module] ?? null;
    //     if (!$model) abort(404);

    //     foreach ($request->ids as $id) {
    //         $record = $model::onlyTrashed()->find($id);
    //         if (!$record) continue;

    //         if ($request->action === 'restore') {
    //             $record->restore();
    //             activity()->performedOn($record)->causedBy(auth()->user())->log("Bulk record restored");
    //         } else {
    //             $record->forceDelete();
    //             activity()->performedOn($record)->causedBy(auth()->user())->log("Bulk record permanently deleted");
    //         }
    //     }

    //     return back()->with('success', '批量操作完成');
    // }

    public function clear(Request $request, $module)
    {
        $model = $this->modules[$module] ?? null;
        if (!$model) abort(404);

        $records = $model::onlyTrashed()->get();
        foreach ($records as $record) {
            $record->forceDelete();
            activity()->performedOn($record)->causedBy(auth()->user())->log("Record deleted via clear trash");
        }

        return back()->with('success', '回收站已清空');
    }
}
