<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileManagerController extends Controller
{
    public function index()
    {
        $files = File::with('uploader')->latest()->paginate(20);
        return view('files.index', compact('files'));
    }
}
