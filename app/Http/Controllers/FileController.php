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

        // $file = File::create([
        //     'filename' => $filename,
        //     'path' => $path,
        //     'mime_type' => $uploadedFile->getClientMimeType(),
        //     'size' => $uploadedFile->getSize(),
        //     'disk' => 'public',
        //     'fileable_type' => $request->fileable_type,
        //     'fileable_id' => $request->fileable_id,
        //     'uploaded_by' => auth()->id(),
        //     'sort_order' => File::where('fileable_type', $request->fileable_type)
        //         ->where('fileable_id', $request->fileable_id)
        //         ->max('sort_order') + 1,
        // ]);

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
}
