<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{   public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->whereNull('deleted_at');

        $keyword = $request->input('keyword');

        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSortFields = ['name', 'email', 'created_at'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // 关键词搜索
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereHas('roles', fn($qr) => $qr->where('name', 'like', "%{$keyword}%"));
            });
        }

        // 筛选条件
        if ($request->filled('filters')) {
            foreach ($request->filters as $filter) {
                $value = $request->input("filter_values.$filter");
                match ($filter) {
                    'name' => $query->whereHas('roles', fn($q) => $q->where('name', $value)),
                    default => null
                };
            }
        }

        $users = $query
            ->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString();
        logger()->info($request->all());

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
        Log::info('Delete method called', ['info' => Auth::id()]);

        if ($user->name === 'admin') {
            return redirect()->back()->with('error', '无法删除超级管理员账户');
        }

        $user->deleted_at = now();
        $user->deleted_by = Auth::id();
        $user->is_active = 0;
        $user->save();

        return redirect()->route('users.index')->with('success', '用户已删除');
    }

    public function batchDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            if ($user->name === 'admin') {
                return redirect()->back()->with('error', '包含超级管理员 admin，操作被中止');
            }
        }


        if (! is_array($ids) || count($ids) === 0) {
            return back()->with('error', '请选择要删除的房源');
        }

        $count = User::whereIn('id', $ids)->update([
            'is_active' => 0,
            'deleted_at' => now(),
            'deleted_by' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', "成功删除 {$count} 个用户");
    }

    public function toggleStatus(Request $request, User $user)
    {
        if ($user->name === 'admin') {
            return response()->json(['message' => '不能禁用超级管理员'], 403);
        }

        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user->is_active = $request->is_active;
        $user->save();

        return response()->json(['message' => '用户状态已更新']);
    }
}
