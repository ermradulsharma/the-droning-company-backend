<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePilotRequest;
use App\Http\Requests\UpdatePilotProfileRequest;
use App\Models\PilotProfile;
use App\Models\PilotEquipments;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Storage;
use Image;

class PilotEquipmentsController extends Controller
{
    public function index()
    {
        $users = PilotEquipments::all()->get();

        return view('admin.pilot.equipments.index', compact('users'));
    }

    public function create(Request $req)
    {
        $userId = $req->id;
        $profileId = $req->pid;

        return view('admin.pilot.equipments.create', compact('userId', 'profileId'));
    }

    public function store(Request $req, PilotEquipments $pilotEqp)
    {
        $uId = str_rot13(base64_decode($req->user_id));
        $pro_id = str_rot13(base64_decode($req->profile_id));
        
        $pilotEqp->pilot_profile_id= $pro_id;
        $pilotEqp->title = $req->title;
        $pilotEqp->image = 'NULL';
        $pilotEqp->manufacturer = $req->manufacturer;
        
        if ($req->hasFile('image')) {
            $image = $req->file('image');
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

            $destinationPath=public_path('/images/'.$pro_id.'/equipment');

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
           
            $pilotEqp->image ='/images/'.$pro_id.'/equipment'.'/'.$image_name;
        }

        $pilotEqp->save();
    
        return redirect()->action([PilotProfileController::class, 'index'])->with('success', 'Successfully pilot equipment created');
    }

    public function edit(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $equipments = PilotEquipments::find($req->pilot_equipment);
        
        return view('admin.pilot.equipments.edit', compact('equipments'));
    }

    public function update(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $pilotEqp = PilotEquipments::find($req->pilot_equipment);
        
        
        $pilotEqp->title = $req->title;
        $pilotEqp->manufacturer = $req->manufacturer;
    
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            //get the image size
            $original_image_size=getimagesize($image);
            list($width, $height)=getimagesize($image);
             
            //let's build the size of array
            $sizes_arr=[ "285,160"];
            $time=time();
        
            //original image
            $image_name =$time.'.' . $image->getClientOriginalExtension();

            $destinationPath=public_path('/images/'.$pilotEqp->pilot_profile_id.'/equipment');

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
         
           
            $pilotEqp->image ='/images/'.$pilotEqp->pilot_profile_id.'/equipment'.'/'.$image_name;
        }

        $pilotEqp->save();

        return redirect()->action([PilotProfileController::class, 'show'], $pilotEqp->pilot_profile_id)->with('success', 'Successfully pilot equipment updated');
    }

    public function show(Request $req)
    {
        abort_if(Gate::denies('pilot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = PilotProfile::with('userOne')->get()->find($req->pilot);

        return view('admin.pilot.profile.show', compact('user'));
    }

    public function destroy(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $pilotEq = PilotEquipments::find($req->pilot_equipment);
        
        $pilotEq->delete();

        return redirect()->action([PilotProfileController::class, 'show'], $pilotEq->pilot_profile_id)->with('success', 'Successfully pilot equipment deleted');
    }
}
