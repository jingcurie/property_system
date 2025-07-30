<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $query = File::with('uploader')->whereNull('deleted_at');
        

        //调用helpers中的functions
        $query = applyKeywordSearch($query, $request);
        $query = applyFilters($query, $request);
        $query = applySorting($query, $request);
        $files = applyPagination($query, $request);

        return view('files.index', compact('files'));
    }

    public function destroy(File $file)
    {
        Storage::disk($file->disk)->delete($file->path);
        $file->delete();

        return response()->json(['success' => true]);
    }
}
