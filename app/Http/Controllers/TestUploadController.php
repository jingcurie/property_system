<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestUploadController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => '未检测到文件'], 400);
        }

        $file = $request->file('file');

        $path = $file->store('uploads', 'public'); // 保存到 storage/app/public/uploads

        return response()->json([
            'success' => true,
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'path' => $path,
        ]);
    }
}
