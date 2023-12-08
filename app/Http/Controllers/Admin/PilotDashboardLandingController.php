<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPilotGalleryRequest;
use App\Http\Requests\StorePilotGalleryRequest;
use App\Http\Requests\UpdatePilotGalleryRequest;
use App\Models\PilotGallery;
use App\Models\PilotProfile;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Storage;
use Image;

class PilotGalleryController extends Controller
{
    public function index()
    {
        $pilotGalleries = PilotGallery::with(['pilot_profile'])->get();


        return view('admin.pilotGalleries.index', compact('pilotGalleries'));
    }

    public function create(Request $request)
    {
        $pilot_profiles = PilotProfile::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $pilot_profile_id=$request->input('id');


        return view('admin.pilotGalleries.create', compact('pilot_profiles', 'pilot_profile_id'));
    }

    public function store(StorePilotGalleryRequest $request)
    {
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

                $destinationPath=public_path('/images/'.$request->pilot_profile_id.'/gallery');

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
         
                PilotGallery::create([
                    'pilot_profile_id'=>$request->pilot_profile_id,
                    'status'=>'1',
                    'image'=>'/images/'.$request->pilot_profile_id.'/gallery/'.$image_name,
                ]);
            }
        }
        
        return redirect()->action([PilotProfileController::class, 'index'])->with('success', 'Profile Gallery added successfully');
    }

    public function edit(PilotGallery $pilotGallery)
    {
        $pilot_profiles = PilotProfile::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pilotGallery->load('pilot_profile');

        return view('admin.pilotGalleries.edit', compact('pilot_profiles', 'pilotGallery'));
    }

    public function update(UpdatePilotGalleryRequest $request, PilotGallery $pilotGallery)
    {
        $data=$request->all();

         
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //get the image size
            $original_image_size=getimagesize($image);
            list($width, $height)=getimagesize($image);
             
            //let's build the size of array
            $sizes_arr=["216,340"];
            $time=time();
        
            //original image
            $image_name =$time.'.' . $image->getClientOriginalExtension();

            $destinationPath=public_path('/images/'.$request->pilot_profile_id.'/gallery');

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
         
           
            $data['image'] ='/images/'.$request->pilot_profile_id.'/gallery/'.$image_name;
        }

        $pilotGallery->update($data);
        return redirect()->action([PilotProfileController::class, 'show'], $request->pilot_profile_id)->with('success', 'Successfully pilot profile gallery updated');
    }

    public function show(PilotGallery $pilotGallery)
    {
        $pilotGallery->load('pilot_profile');

        return view('admin.pilotGalleries.show', compact('pilotGallery'));
    }

    public function destroy(PilotGallery $pilotGallery)
    {
        $pilotGallery->delete();

        return redirect()->action([PilotProfileController::class, 'show'], $pilotGallery->pilot_profile_id)->with('success', 'Successfully pilot profile gallery deleted');
    }

    public function massDestroy(MassDestroyPilotGalleryRequest $request)
    {
        PilotGallery::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
