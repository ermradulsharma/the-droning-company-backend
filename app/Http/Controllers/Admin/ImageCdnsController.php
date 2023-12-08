<?php

namespace App\Http\Controllers\Admin;

use Gate;

use App\Models\ImageCdn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageCdnsController extends Controller
{
    public function index()
    {
        $imagescdn =ImageCdn::latest('id')->get();

        return view('admin.cdn.index', compact('imagescdn'));
    }

    public function create()
    {
        return view('admin.cdn.create');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $img= new ImageCdn();
            $img->image=Storage::disk('public_uploads')
                    ->put('images/cdn', $request->image);

            $img->save();
        }

        return redirect()->route('admin.image-cdn.index');
    }
}
