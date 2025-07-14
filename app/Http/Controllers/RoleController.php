<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with(['permissions'])->withCount('users')->get()->map(function ($role) {
            // 获取权限对象集合
            $role->display_permissions = $role->permissions;
            return $role;
        });

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('group')->get()->groupBy('group');
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            //'description' => $validated['description'] ?? null, // 如果字段可空
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $users = $role->users()->with('roles')->get(); // 获取拥有该角色的用户
        $users = $role->users()->paginate(10); // 确保是 paginate()

        return view('roles.show', [
            'role' => $role,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Role $role)
    // {
    //     // 获取当前角色的所有权限名称（数组形式）
    //     $rolePermissions = $role->permissions->pluck('name')->toArray();

    //     return view('roles.edit', [
    //         'role' => $role,
    //         'rolePermissions' => $rolePermissions,
    //     ]);
    // }


    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->get()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // 防止误删超级管理员角色
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Admin role cannot be deleted.');
        }

        // 解除所有用户与此角色绑定
        $role->users()->detach();

        // 解除角色拥有的所有权限（可选）
        $role->permissions()->detach();

        // 日志记录（如果你有 log_operation 函数）
        // log('role.delete', "Deleted role {$role->name} (ID: {$role->id})");

        // 删除角色
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }


    public function batchDelete(Request $request, Role $role)
    {
        $request->validate([
            'user_ids' => 'required|string',
        ]);

        $userIds = explode(',', $request->input('user_ids'));

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $user->removeRole($role->name);
        }

        return back()->with('success', 'Selected users removed from the role.');
    }

    public function removeUser(Role $role, User $user)
    {
        $user->removeRole($role->name);
        return back()->with('success', '用户已从该角色移除');
    }
}
