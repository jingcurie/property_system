<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RentalApplicationController;
use App\Http\Controllers\MediaController;
use App\Models\PropertyMedia;
use App\Http\Controllers\FileController;

use App\Http\Controllers\TestUploadController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FileManagerController;

// === 公共页面 ===
Route::get('/', fn() => view('welcome'));

// === 登录登出（必须放在 auth 外）===
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 语言切换路由
Route::get('/lang/{locale}', function ($locale) {
    // 只允许中英文切换
    if (!in_array($locale, ['en', 'zh'])) {
        abort(400);
    }

    // 保存语言到 Session
    session(['locale' => $locale]);
    app()->setLocale($locale);

    // 调试输出
    // dd([
    //     '语言：{{ app()->getLocale() }}' => app()->getLocale(),
    //     'selected_locale' => $locale,
    //     'session_locale' => session('locale'),
    //     'current_app_locale' => app()->getLocale()
    // ]);

    // 切换语言后跳转回原页面
    return redirect()->back();
})->name('lang.switch');

// Route::get('/session-debug', function() {
//     return response()->json([
//         'session_id' => session()->getId(),
//         'session_data' => session()->all(),
//         'app_locale' => app()->getLocale(),
//         'storage_path' => storage_path('framework/sessions'),
//         'session_files' => glob(storage_path('framework/sessions/*'))
//     ]);
// });

// Route::get('/lang/{locale}', function ($locale) {
//     if (!in_array($locale, ['en', 'zh'])) {
//         abort(400);
//     }
//     session(['locale' => $locale]);
//     app()->setLocale($locale);
//     \Illuminate\Support\Facades\Config::set('app.locale', $locale);
//     return redirect()->back();
// });

// Route::view('/lang-test', 'lang-test');

// === 登录后才能访问的路由 ===
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 房源管理
    Route::resource('properties', PropertyController::class);
    Route::post('/properties/batch-delete', [PropertyController::class, 'batchDelete'])->name('properties.batchDelete');
    Route::get('/properties/export', [PropertyController::class, 'export'])->name('properties.export');

    // 用户管理（管理员限定）
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/batch-delete', [UserController::class, 'batchDelete'])->name('users.batchDelete');
        Route::delete('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    });

    // 角色管理
    Route::resource('roles', RoleController::class);
    Route::delete('/roles/{role}/users/{user}', [RoleController::class, 'removeUser'])->name('roles.users.remove');
    Route::post('/roles/{role}/users/batch-remove', [RoleController::class, 'batchDelete'])->name('roles.users.batchDelete');

    // 权限管理
    Route::resource('permissions', PermissionController::class)->except(['show', 'edit', 'update']);
    Route::delete('permissions/bulk-delete', [PermissionController::class, 'bulkDelete'])->name('permissions.bulk-delete');

    // 媒体上传
    Route::post('/media/temp-upload', [MediaController::class, 'tempUpload'])->name('media.tempUpload');
    Route::get('/media/property/{path}', [MediaController::class, 'show'])->where('path', '.*');

    // 房源媒体接口
    Route::get('/property/{id}/media', function ($id) {
        $media = PropertyMedia::where('property_id', $id)->get()->map(function ($item) {
            return [
                'filename' => basename($item->file_path),
                'type' => $item->media_type,
            ];
        });
        return response()->json($media);
    });

    // 租赁申请
    Route::get('properties/{id}/apply', [RentalApplicationController::class, 'createFromProperty'])->name('applications.create');
    Route::post('properties/{property}/apply', [RentalApplicationController::class, 'store'])->name('applications.store');
    // Route::get('applications', [RentalApplicationController::class, 'index'])->name('applications.index');
    // Route::patch('applications/{application}/status', [RentalApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
    // Route::delete('applications/{application}', [RentalApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::resource('rental_applications', RentalApplicationController::class);
    Route::post('/rental_applications/batch-delete', [RentalApplicationController::class, 'batchDelete'])->name('rental_applications.batchDelete');
    Route::post('rental_applications/batch-approve', [RentalApplicationController::class, 'batchApprove'])->name('rental_applications.batchApprove');
    Route::post('rental_applications/batch-reject', [RentalApplicationController::class, 'batchReject'])->name('rental_applications.batchReject');
    Route::get('rental_applications/export', [RentalApplicationController::class, 'export'])->name('rental_applications.export');
    Route::post('rental_applications/{id}/update-note', [RentalApplicationController::class, 'updateNote']);
    // routes/web.php
    Route::post('/rental_applications/{id}/update-status', [RentalApplicationController::class, 'updateStatus']);



    // 动态筛选字段
    Route::get('/filters/field', function (Request $request) {
        $filter = $request->query('filter');
        $module = $request->query('module');
        if (!in_array($module, ['properties', 'users', 'roles', 'permissions', 'rental_applications'])) {
            abort(403, '非法访问');
        }
        $viewPath = $module . '.partials.filter_fields';
        return view($viewPath, ['filter' => $filter, 'value' => null]);
    });

    Route::prefix('files')->name('files.')->group(function () {
        Route::post('upload', [FileController::class, 'upload'])->name('upload');
        Route::post('save', [FileController::class, 'store']);
        Route::post('{file}/update-note', [FileController::class, 'updateNote'])->name('updateNote');
        Route::post('{file}/mark-cover', [FileController::class, 'markAsCover'])->name('markCover');
        Route::delete('{file}', [FileController::class, 'destroy'])->name('destroy');
        Route::get('{file}/preview', [FileController::class, 'preview'])->name('preview');
        Route::get('{file}/download', [FileController::class, 'download'])->name('download');
    });

    //dashboard search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    //email
    Route::post('/email/send', [EmailController::class, 'send'])->name('email.send');

    //files
    Route::get('/file-center', [FileManagerController::class, 'index'])->name('file-center.index');
    Route::get('/files/{file}/preview', [FileManagerController::class, 'preview'])->name('files.preview');
    Route::get('/files/{file}/download', [FileManagerController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}', [FileManagerController::class, 'destroy'])->name('files.destroy');
    Route::post('/files/batch-delete', [FileManagerController::class, 'batchDelete'])->name('files.batchDelete');

    // 可选：Email 路由（如果你启用了“Email”操作）
    Route::post('/files/{file}/email', [EmailController::class, 'send'])->name('files.email');

    Route::get('/test-pdf', fn() => view('preview-pdf'));
});
