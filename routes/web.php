<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\MediaController;
use App\Http\Controllers\RentalApplicationController;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

// Route::view('/test-delete-form', 'test-delete-form');

// Route::post('/test/test-delete', function (Request $request) {
//     return '已成功接收到 POST 请求，删除了 ID：'.implode(', ', $request->input('selected_ids', []));
// })->name('test.delete');


use App\Http\Controllers\PropertyController;
// 批量删除房源
Route::post('/properties/batch-delete', [PropertyController::class, 'batchDelete'])->name('properties.batchDelete');
// 导出 CSV
Route::get('/properties/export', [PropertyController::class, 'export'])->name('properties.export');

Route::resource('properties', PropertyController::class);
Route::get('properties/{property}/apply', [RentalApplicationController::class, 'create'])->name('applications.create');
Route::post('properties/{property}/apply', [RentalApplicationController::class, 'store'])->name('applications.store');
Route::get('applications', [RentalApplicationController::class, 'index'])->name('applications.index');
Route::patch('applications/{application}/status', [RentalApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
Route::delete('applications/{application}', [RentalApplicationController::class, 'destroy'])->name('applications.destroy');
// web.php
Route::get('/dashboard', function () {
    return view('dashboard'); // 创建 resources/views/dashboard.blade.php
})->name('dashboard');

Route::post('/media/temp-upload', [MediaController::class, 'tempUpload'])->name('media.tempUpload');

// routes/web.php
Route::get('/media/property/{path}', [MediaController::class, 'show'])->where('path', '.*');

use App\Models\PropertyMedia;

Route::get('/property/{id}/media', function ($id) {
    $media = PropertyMedia::where('property_id', $id)->get()->map(function ($item) {
        return [
            'filename' => basename($item->file_path),
            'type' => $item->media_type,
        ];
    });

    return response()->json($media);
});

Route::get('/filters/field', function (Request $request) {
    $filter = $request->query('filter');

    return view('properties.partials.filter_fields', ['filter' => $filter, 'value' => null]);
});

Route::middleware(['role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware(['permission:property.edit'])->group(function () {
    Route::get('/properties/{id}/edit', [PropertyController::class, 'edit']);
});

Route::get('/users/create', [UserController::class, 'create'])->middleware('role:admin');

use App\Http\Controllers\Auth\LoginController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout')->middleware('auth');


Route::resource('users', UserController::class);
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
// 批量删除用户
Route::post('/users/batch-delete', [PropertyController::class, 'batchDelete'])->name('users.batchDelete');
Route::delete('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');

use App\Http\Controllers\RoleController;
Route::resource('roles', RoleController::class);
Route::delete('/roles/{role}/users/{user}', [RoleController::class, 'removeUser'])->name('roles.users.remove');
Route::post('/roles/{role}/users/batch-remove', [RoleController::class, 'batchDelete'])->name('roles.users.batchDelete');

use App\Http\Controllers\PermissionController;
Route::middleware(['auth'])->group(function () {
    Route::resource('permissions', PermissionController::class)->only(['index', 'create', 'store']);
});

Route::delete('permissions/bulk-delete', [PermissionController::class, 'bulkDelete'])->name('permissions.bulk-delete');
Route::resource('permissions', PermissionController::class)->except(['show', 'edit', 'update']);
Route::delete('permissions/bulk-delete', [PermissionController::class, 'bulkDelete'])->name('permissions.bulk-delete');
