<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\FileAttachmentMail;
use Illuminate\Http\Request;
use App\Models\File;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        try {
            $request->validate([
                'to' => 'required|email',
                'subject' => 'required|string',
                'body' => 'required|string',
                'file_ids' => 'required|string', // 改成字符串（用逗号隔开的多个 ID）
            ]);

            // 拆分多个 ID，查询文件
            $ids = (array) $request->file_ids;
            $fileIds = (array) $request->file_ids;

            if (count($fileIds) === 0) {
                return response()->json(['error' => '请选择至少一个附件'], 422);
            }

            $files = File::whereIn('id', $fileIds)->get();

            // 发送邮件（传多个附件对象）
            Mail::to($request->to)->send(
                new FileAttachmentMail($request->subject, $request->body, $files)
            );

            return response()->json(['message' => '邮件发送成功']);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
