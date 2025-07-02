<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // 展示权限列表
    public function index()
    {
        $permissions = Permission::with('roles')->orderBy('created_at', 'desc')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    // 展示创建表单
    public function create()
    {
        return view('permissions.create');
    }

    // 保存权限
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable|string',
            'guard_name' => 'required|string'
        ]);

        $permission = new Permission();
        $permission->name = $validated['name'];
        $permission->guard_name = $validated['guard_name'];

        if (Permission::getModel()->isFillable('description')) {
            $permission->description = $validated['description'] ?? null;
        }

        $permission->save();

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    // 删除单个权限
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted.');
    }

    // 批量删除权限
    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('selected_ids'));

        if (empty($ids)) {
            return redirect()->back()->with('error', '未选择任何权限');
        }

        Permission::whereIn('id', $ids)->delete();

        return redirect()->route('permissions.index')->with('success', '权限已批量删除');
    }
}
