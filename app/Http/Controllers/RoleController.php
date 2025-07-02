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
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
            'decription' => 'required|description',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);
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
    public function edit(Role $role)
    {
        // 获取当前角色的所有权限名称（数组形式）
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', [
            'role' => $role,
            'rolePermissions' => $rolePermissions,
        ]);
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
        if ($role->name === 'admin') {
            return redirect()->back()->with('error', 'Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
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
