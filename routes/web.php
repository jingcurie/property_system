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
use App\Http\Controllers\ApplicantController;
use App\Models\PropertyMedia;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PropertyOwnershipController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\LeaseController;

use App\Http\Controllers\TestUploadController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;


// === 公共页面 ===
Route::get('/', fn() => view('welcome'));

// === 登录登出（必须放在 auth 外）===
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/lang/{locale}', function ($locale) {
    // 语言代码映射
    $localeMap = [
        'en' => 'en',
        'zh' => 'zh',
        'zh-CN' => 'zh',  // 支持标准格式
    ];

    if (!array_key_exists($locale, $localeMap)) {
        abort(400);
    }

    $actualLocale = $localeMap[$locale];
    session(['locale' => $actualLocale]);
    app()->setLocale($actualLocale);

    return redirect()->back();
})->name('lang.switch');

// === 登录后才能访问的路由 ===
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 房源管理
    Route::resource('properties', PropertyController::class);
    Route::post('/properties/batch-delete', [PropertyController::class, 'batchDelete'])->name('properties.batchDelete');
    Route::get('/properties/export', [PropertyController::class, 'export'])->name('properties.export');
    Route::post('/properties/{property}/add-owner', [PropertyController::class, 'addOwner'])->name('properties.addOwner');

    Route::post('properties/{property}/owners', [PropertyOwnershipController::class, 'store'])->name('owners.store');
    Route::put('/properties/{property}/owners/{owner}', [PropertyOwnershipController::class, 'update']);
    Route::delete('/properties/{property}/owners/{owner}', [PropertyOwnershipController::class, 'destroy'])->name('owners.softDestroy');
    Route::resource('owners', OwnerController::class);



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
        $filters = json_decode(urldecode($request->query('filters')), true); // 解码 JSON

        return view("components.filters.filter_fields", [
            'filter' => $filter,
            'value' => null,
            'filterFields' => $filters
        ]);
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
    // Route::get('/search', [SearchController::class, 'index'])->name('search');

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

    //leases
    Route::resource('leases', LeaseController::class);


    //applicants
    Route::resource('applicants', ApplicantController::class);

    //notification

    // Route::get('/notifications/unread-count', function (Request $request) {
    //     $count = Notification::where('user_id', $request->user()->id)
    //                         ->where('is_read', false)
    //                         ->count();
    //     return response()->json(['count' => $count]);
    // });

    Route::post('/notifications/generate', [NotificationController::class, 'generate']);
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread']);
});
