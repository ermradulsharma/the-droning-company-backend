<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Storage;
use Image;

class PhotoGalleryController extends Controller
{
    public function index()
    {
        $photo_galleries = PhotoGallery::latest()->get();


      

        return view('admin.gallery.index', compact('photo_galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $data=$request->all();
        
        if ($request->has('image')) {
            foreach ($request->image as $image) {
                //get the image size
                $original_image_size=getimagesize($image);
                list($width, $height)=getimagesize($image);
             
                //let's build the size of array
                $sizes_arr=[
                "400,400",
             ];
                $time=time();
        
                //original image
                $image_name =$time.'.' . $image->getClientOriginalExtension();

                $destinationPath=public_path('/images/photo_gallery');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
            
                $resize_image = Image::make($image->getRealPath());

                foreach ($sizes_arr as $size_value) {
                    $array = explode(',', $size_value);
                    $new_width = $array[0];
                    $new_height = $array[1];
                    $new_image_name =$time.'-'.$new_width.'x'.$new_height.'.'.$image->getClientOriginalExtension();
                    $resize_image->resize($new_width, $new_height, function ($constraint) {
                        //$constraint->aspectRatio();
                    })->save($destinationPath . '/' . $new_image_name);
                }
                //original path directory.

                $image->move($destinationPath, $image_name);
                $data['image']='/images/photo_gallery/'.$image_name;
                $data['status']=1;
            }

            PhotoGallery::create($data);
        }
        
        return redirect()->action([PhotoGalleryController::class, 'index'])->with('success', 'Successfully Gallery Created');
    }

    public function edit(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photo_gallery = PhotoGallery::find($req->photo_gallery);
        
        return view('admin.gallery.edit', compact('photo_gallery'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $photoGallery = PhotoGallery::find($request->photo_gallery);
    
        if ($request->hasFile('image')) {
            $img = Storage::disk('public_uploads')->put('/images/photo_gallery', $request->image);
            $photoGallery->image = $img ;
        }
           
        $photoGallery->status = $request->status;
        $photoGallery->image_text = $request->image_text;
        $photoGallery->image_link = $request->image_link;
        $photoGallery->save();
        
        return redirect()->action([PhotoGalleryController::class, 'index'])->with('success', 'Successfully Gallery Updated');
    }


    public function destroy(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $pilotEq = PhotoGallery::find($req->photo_gallery);
        
        $pilotEq->delete();

        return redirect()->action([PhotoGalleryController::class, 'index'])->with('success', 'Successfully Gallery Deleted');
    }
}
