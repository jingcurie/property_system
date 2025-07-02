<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function tempUpload(Request $request)
    {
        if (! $request->hasFile('file')) {
            return response()->json(['error' => '无上传文件'], 400);
        }

        $file = $request->file('file');

        $ext = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString().'.'.$ext;

        // 保存至临时目录 storage/app/temp/property-media
        // $path = $file->storeAs('temp/property-media', $filename);

        $tempDir = 'temp/property-media';

        // 若目录不存在则创建
        if (! Storage::exists($tempDir)) {
            Storage::makeDirectory($tempDir);
        }

        // 保存文件
        $path = $file->storeAs($tempDir, $filename);

        return response()->json([
            'id' => $filename,
            'path' => $path,
        ]);
    }

    public function show($path)
    {
        // 1. 统一路径格式（适配您的存储结构）
        $filePath = "property_media/{$path}";

        // 2. 检查文件是否存在
        if (! Storage::disk('private')->exists($filePath)) {
            abort(404, "文件不存在: {$filePath}");
        }

        // 3. 获取文件的绝对路径
        $absolutePath = Storage::disk('private')->path($filePath);

        // 4. 动态检测 MIME 类型（无需依赖 Storage::mimeType）
        $mimeType = mime_content_type($absolutePath);

        // 5. 返回文件响应
        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
