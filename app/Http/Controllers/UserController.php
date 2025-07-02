<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware ('role:admin'); // 👈 这里就是触发报错的地方
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        // 排序字段与方向，设置默认值
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // 允许排序的字段，防止 SQL 注入
        $allowedSortFields = ['name', 'email', 'created_at'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $users = User::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString(); // 保持查询参数在分页中不丢失

        return view('users.index', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ], [
            'email.unique' => '该邮箱已被注册，请更换一个邮箱。',
        ]);

        $avatarPath = null;

        // 优先使用上传头像
        if ($request->hasFile('avatar_uploaded')) {
            $file = $request->file('avatar_uploaded');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatars/customers'), $filename);
            $avatarPath = 'customers/' . $filename;
        } elseif ($request->filled('avatar')) {
            // 使用固定头像
            $avatarPath = $request->avatar;
        }

        // 创建用户
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $avatarPath, // 使用处理好的路径
        ]);

        // 分配角色
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', '用户创建成功');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // 可选密码更新
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 头像处理
        if ($request->avatar_uploaded) {
            $filename = $request->avatar_uploaded;

            // 移动临时头像
            $tempPath = public_path("temp/avatars/{$filename}");
            $targetPath = public_path("avatars/{$filename}");

            if (File::exists($tempPath)) {
                File::move($tempPath, $targetPath);
            }

            // 删除旧上传头像（如非默认）
            if ($user->avatar && File::exists(public_path('avatars/' . $user->avatar)) && $user->avatar !== 'default.png') {
                File::delete(public_path('avatars/' . $user->avatar));
            }

            $user->avatar = $filename;
        } elseif ($request->avatar) {
            $user->avatar = $request->avatar; // 选择固定头像
        }

        $user->save();

        // 角色同步
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', '用户已更新');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_active' => 0,
            'deleted_at' => now(),
            'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', '用户已删除');
    }

    public function batchDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (! is_array($ids) || count($ids) === 0) {
            return back()->with('error', '请选择要删除的房源');
        }

        $count = User::whereIn('user_id', $ids)->update([
            'is_active' => 0,
            'deleted_at' => now(),
            'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', "成功删除 {$count} 个用户");
    }
}
