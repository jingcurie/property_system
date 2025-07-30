<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * 文件列表查询（按所属对象 + tag + search + 是否是首图 + 排序）
     */
    public function index(Request $request)
    {
        $query = File::query();

        $query->where('fileable_type', $request->input('fileable_type'))
            ->where('fileable_id', $request->input('fileable_id'));

        if ($tag = $request->input('tag')) {
            $query->where('tag', $tag);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        return response()->json(
            $query->orderBy('sort_order')->get()
        );
    }

    /**
     * 文件上传
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'fileable_type' => 'required|string',
            'fileable_id' => 'nullable|integer',
        ]);

        $uploadedFile = $request->file('file');
        $filename = $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->store("uploads/temp", 'public');

        return response()->json([
            'success' => true,
            'file' => [
                'id' => null, // 因为还没存库
                'title' => $filename,
                'filename' => $filename,
                'path' => $path,
                'fileable_type' => $request->fileable_type,
                'fileable_id' => $request->fileable_id,
                'mime_type' => $uploadedFile->getClientMimeType(),
                'size' => $uploadedFile->getSize(),
                'disk' => 'public',
                'uploaded_by' => auth()->id(),
                'category' => 'uncategorized',
            ]
        ]);

        return response()->json(['success' => true, 'file' => $file]);
    }

    /**
     * 更新文件备注 / tag / 私有化
     */
    public function update(Request $request, File $file)
    {
        $file->update($request->only(['tag', 'description', 'is_private']));
        return response()->json(['success' => true]);
    }

    /**
     * 删除文件
     */
    public function destroy(File $file)
    {
        Storage::disk($file->disk)->delete($file->path);
        $file->delete();

        return response()->json(['success' => true]);
    }

    /**
     * 设置文件为首图
     */
    public function markAsCover(File $file)
    {
        File::where('fileable_type', $file->fileable_type)
            ->where('fileable_id', $file->fileable_id)
            ->update(['is_cover' => false]);

        $file->update(['is_cover' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * 重新排序
     */
    public function reorder(Request $request)
    {
        foreach ($request->input('orders') as $index => $id) {
            File::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * 文件预览/下载
     */
    public function preview(File $file)
    {
        return Storage::disk($file->disk)->response($file->path);
    }

    public function download(File $file)
    {
        return Storage::disk($file->disk)->download($file->path, $file->filename);
    }

    public function store(Request $request)
    {
        $attachments = $request->input('attachments', []);
        $saved = [];

        foreach ($attachments as $item) {
            // 原始临时路径
            $tempPath = storage_path('app/public/' . $item['path']);
            if (!file_exists($tempPath)) {
                continue; // 跳过找不到的文件
            }
            // 模块路径识别
            $fileableType = $item['fileable_type'] ?? 'unknown';
            $fileableId = $item['fileable_id'] ?? null;
            if (!$fileableId) {
                continue; // 没有目标ID，跳过
            }
            // 模块名用于目录，如 App\Models\RentalApplication → rental_applications
            $moduleName = \Str::plural(\Str::kebab(class_basename($fileableType)));

            // 生成新文件名与路径
            $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
            $newFileName = (string) Str::uuid() . '.' . $extension;
            $newRelativeDir = "uploads/files/{$moduleName}/{$fileableId}";
            $newRelativePath = "{$newRelativeDir}/{$newFileName}";
            $newStoragePath = storage_path("app/public/{$newRelativePath}");
            // 创建目录并移动文件
            \File::ensureDirectoryExists(dirname($newStoragePath));
            \File::move($tempPath, $newStoragePath);
            // 保存记录到数据库
            $file = new \App\Models\File(); // 或 Attachment，根据你的模型名
            $file->title = $item['title'] ?? null;
            $file->filename = $item['filename'] ?? null;
            $file->path = $newRelativePath;
            $file->fileable_type = $fileableType;
            $file->fileable_id = $fileableId;
            $file->category = $item['category'] ?? 'uncategorized';
            $file->description = $item['description'] ?? '';
            $file->is_cover = $item['is_cover'] ?? false;
            $file->mime_type = $item['mime_type'] ?? null;
            $file->size = $item['size'] ?? 0;
            $file->disk = 'public';
            $file->uploaded_by = auth()->id();

            $file->save();

            $saved[] = $file;
        }

        return response()->json([
            'success' => true,
            'savedFiles' => $saved,
        ]);
    }
}
