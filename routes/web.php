<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RentalApplicationController;
use App\Http\Controllers\MediaController;

use Illuminate\Http\Request;

Route::view('/test-delete-form', 'test-delete-form');

Route::post('/test/test-delete', function (Request $request) {
    return '已成功接收到 POST 请求，删除了 ID：' . implode(', ', $request->input('selected_ids', []));
})->name('test.delete');



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

