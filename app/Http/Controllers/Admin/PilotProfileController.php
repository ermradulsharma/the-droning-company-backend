<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Image;
use Storage;
use App\Models\User;
use App\Models\Skill;
use App\Models\State;
use App\Models\Country;
use App\Models\RoleUser;
use App\Models\PilotRate;
use App\Models\PilotSkills;
use App\Models\PilotAddress;
use App\Models\PilotGallery;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StorePilotRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdatePilotProfileRequest;
use Carbon\Carbon;

class PilotProfileController extends Controller
{
    public function index(PilotProfile $request)
    {
        $users = PilotProfile::with(['users'])->latest('id')->get();

        return view('admin.pilot.profile.index', compact('users'));
    }

    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 3);
        })->active()->get();

   
        
        $skills = Skill::active()->get()->pluck('skill_name', 'id');
        return view('admin.pilot.profile.create', compact('users', 'skills'));
    }

    public function store(Request $req, PilotProfile $pilotProfile, PilotRate $rate)
    {
        $pilotProfile->user_id= $req->user_id;
        $pilotProfile->title = $req->title;
        $pilotProfile->slug = $req->slug;
        $pilotProfile->image = 'NULL';
        $pilotProfile->is_certified = $req->is_certified;
        $pilotProfile->travel_option = $req->travel_option;
        $pilotProfile->is_featured = $req->is_featured;
        $pilotProfile->description = $req->description;
		$pilotProfile->home_featured = $req->home_featured;
        $pilotProfile->home_featured_updated_at	 = $req->home_featured == 1 ? Carbon::now()->toDateTimeString() : NULL;

        
        $pilotProfile->short_description = $req->short_description;
        $pilotProfile->metatitle = $req->metatitle;
        $pilotProfile->metakeyword = $req->metakeyword;
        $pilotProfile->metadescription = $req->metadescription;

        $pilotProfile->save();

        $allskills = $req->skill;
        $profile_id = $pilotProfile->id;

        for ($cnt=0; $cnt < count($allskills); $cnt++) {
            $all_sk[] = [
                'pilot_profile_id' => $profile_id,
                'skill_id' => $allskills[$cnt]
            ];
        }
        
        PilotSkills::insert($all_sk);                                               // Storing multiple skills into pilot_skills
        
        $rate->pilot_profile_id = $profile_id;
        $rate->rate = $req->rate;
        $rate->save();
        
    

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            //get the image size
            $original_image_size=getimagesize($image);
            list($width, $height)=getimagesize($image);
             
            //let's build the size of array
            $sizes_arr=[
                "275,275",
                "160,160"  //blog sidebar
             ];
            $time=time();
        
            //original image
            $image_name =$time.'.' . $image->getClientOriginalExtension();

            $destinationPath=public_path('/images/'.$profile_id.'/profile');

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
         
            $PilotProfile = PilotProfile::find($profile_id);
            $PilotProfile->image ='/images/'.$profile_id.'/profile/'.$image_name;
            $PilotProfile->save();
        }


        if ($req->hasFile('license_image')) {
            $allConfig ='images/' .$profile_id. '/profile/';
            $PilotProfile = PilotProfile::find($profile_id);
            $PilotProfile->license_image=Storage::disk('public_uploads')->put($allConfig, $req->license_image);
            $PilotProfile->save();
        }

        return redirect()->action([PilotProfileController::class, 'index'])->with('success', 'Successfully profile created');
    }

    public function edit(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $user = PilotProfile::with('userOne')->get()->find($req->pilot);

      
        
        $rate = PilotRate::where('pilot_profile_id', '=', $req->pilot)
                          ->where('status', '=', '1')
                          ->latest('id')
                          ->get();
        if (sizeof($rate)) {
            $pilot_rate = $rate[0]->rate;
        } else {
            $pilot_rate = '0';
        }
        
        $skills = Skill::all()->pluck('skill_name', 'id');

        $pilot_skills = PilotSkills::where('pilot_profile_id', '=', $req->pilot)
                        ->where('status', '=', '1')
                        ->where('deleted_at', '=', null)->get();
        
        $total_skills = [];
        
        foreach ($pilot_skills as $key => $value) {
            $total_skills[] = $value->skill_id;
        }

        return view('admin.pilot.profile.edit', compact('user', 'pilot_rate', 'skills', 'total_skills'));
    }

    public function update(UpdatePilotProfileRequest $request, PilotRate $rate)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $data=$request->all();
        $profile_id = $request->profile_id;
        
        $pilotProfile = PilotProfile::find($profile_id);
       

        
        PilotSkills::where('pilot_profile_id', $profile_id)->delete();
        
        $allskills = $request->skill;
        
        for ($cnt=0; $cnt < count($allskills); $cnt++) {
            $all_sk[] = [
                'pilot_profile_id' => $profile_id,
                'skill_id' => $allskills[$cnt]
            ];
        }
        
        PilotSkills::insert($all_sk);
        
        PilotRate::where('pilot_profile_id', $profile_id)->update(['status' => '0']);
        
        
        $rate->pilot_profile_id = $profile_id;
        $rate->rate = $request->rate;
        $rate->save();
        
       
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //get the image size
            $original_image_size=getimagesize($image);
            list($width, $height)=getimagesize($image);
             
            //let's build the size of array
            $sizes_arr=[
                "275,275",
                 "160,160"  //blog sidebar
             ];
            $time=time();
        
            //original image
            $image_name =$time.'.' . $image->getClientOriginalExtension();

            $destinationPath=public_path('/images/'.$profile_id.'/profile');

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
            $data['image']='/images/'.$profile_id.'/profile/'.$image_name;
        }
 
        if ($request->hasFile('license_image')) {
            $allConfig ='images/' .$profile_id. '/profile/';
            $data['license_image']=Storage::disk('public_uploads')->put($allConfig, $request->license_image);
        }

        $data['home_featured_updated_at']= $request->home_featured == 1 ? Carbon::now()->toDateTimeString() : NULL;

        $pilotProfile->update($data);
        $user=User::where('id', $request->user_id)->first();
        $user->first_name=$request->first_name;
        $user->last_name=$request->last_name;
        $user->save();
        
        return redirect()->action([PilotProfileController::class, 'index'])->with('success', 'Successfully profile updated');
    }

    public function show(Request $req)
    {
        abort_if(Gate::denies('pilot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $country = Country::all()->pluck('name', 'id');
        
        $skills = Skill::where("deleted_at", null)->pluck('skill_name', 'id');
        $address = PilotAddress::leftjoin('state', 'state.id', '=', 'pilot_address.state')->where("pilot_address.pilot_profile_id", $req->pilot)->where("pilot_address.deleted_at", null)->get(['pilot_address.*','state.name']);
        $rate = PilotRate::where('pilot_profile_id', '=', $req->pilot)->where('status', '=', '1')->get();
        
        $user = PilotProfile::with('userSkill')->get()->find($req->pilot);
        
        $equipments = DB::table('pilot_equipments')->where([['pilot_profile_id', '=', $req->pilot],
                            ['deleted_at', '=', null]
                            ])->get();
        $videos = DB::table('pilot_videos')->where([['pilot_profile_id', '=', $req->pilot],
                            ['deleted_at', '=', null]
                            ])->get();

        $galleries =PilotGallery::where('pilot_profile_id', $req->pilot)->latest()->get();

        
        
        if (sizeof($rate)) {
            $pilot_rate = $rate[0]->rate;
        } else {
            $pilot_rate = '0';
        }
        return view('admin.pilot.profile.show', compact('country', 'user', 'address', 'equipments', 'videos', 'galleries', 'skills', 'pilot_rate'));
    }

    public function destroy(Request $req)
    {
        $pilotProfile = PilotProfile::find($req->pilot);
        $pilotProfile->delete();
        return back();
    }

    public function massDestroy(Request $request)
    {
        PilotProfile::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massActiveStatus(Request $request)
    {
        PilotProfile::whereIn('id', $request->ids)->update(['status' => '1']);
//
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massInActiveStatus(Request $request)
    {
        PilotProfile::whereIn('id', $request->ids)->update(['status' => '0']);
//
        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function massCertifiedYes(Request $request)
    {
        PilotProfile::whereIn('id', $request->ids)->update(['is_certified' => 'Yes']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massCertifiedNo(Request $request)
    {
        PilotProfile::whereIn('id', $request->ids)->update(['is_certified' => 'No']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    
    public function massFeatureYes(Request $request)
    {
        PilotProfile::whereIn('id', $request->ids)->update(['is_featured' => 'Yes']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massFeatureNo(Request $request)
    {
        PilotProfile::whereIn('id', $request->ids)->update(['is_featured' => 'No']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    
    public function getSlug(Request $request)
    {
        $id = $request['sendInfo']['ids'];
        $users = User::find($id);

        $slug = $users->slug;
        
        if ($slug == '') {
            $fname = preg_split("/[\s,]+/", $users->first_name);
            $lname = preg_split("/[\s,]+/", $users->last_name);
            
            $slug = $fname[0].'-'.$lname[0];
        }
        
        $status = ['status'=>'200','slug'=>$slug];
        return json_encode($status);
        exit();
    }
}
