<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class ImageCategoryController extends Controller
{
    public function store(Request $request)
    {
        $file=Storage::disk('public_uploads')->put('images/tincy', $request->file);
        $uploaded=[
                 'location'=>asset($file),
               ];

        return json_encode($uploaded);
    }
}
