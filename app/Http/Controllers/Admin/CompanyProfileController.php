<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Image;
use Storage;
use App\Models\User;
use App\Models\Service;
use App\Models\Country;
use App\Models\PilotRate;
use App\Models\CompanyService;
use App\Models\CompanyGallery;
use App\Models\CompanyVideo;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use File;

class CompanyProfileController extends Controller
{
    public function index(Request $request)
    {
        $companies = CompanyProfile::with(['user'])->latest('id')->get();
        return view('admin.company.profile.index', compact('companies'));
    }

    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 4);
        })->active()->get();
        $services = Service::active()->get()->pluck('title', 'id');
        $country = Country::all()->pluck('name', 'id');
        return view('admin.company.profile.create', compact('users', 'services', 'country'));
    }

    public function store(Request $req, CompanyProfile $profile)
    {
        $profile->user_id= $req->user_id;
        $profile->title = $req->title;
        $profile->slug = $req->slug;
        $profile->address = $req->address;
        $profile->suite = $req->suite;
        $profile->city = $req->city;
        $profile->country = $req->country_id;
        $profile->state = $req->state;
        $profile->zip_code = $req->zip_code;
        $profile->contact_person = $req->contact_person;
        $profile->website = $req->website;
        $profile->email = $req->email;
        $profile->phone = $req->phone;
        $profile->working_hours = $req->working_hours;
        $profile->service_1 = $req->service_1;
        $profile->service_2 = $req->service_2;
        $profile->service_3 = $req->service_3;
        $profile->logo = '';
        $profile->featured_image = '';
        $profile->profile_img_1 = '';
        $profile->profile_img_2 = '';
        $profile->profile_img_3 = '';
        $profile->profile_img_4 = '';
        $profile->profile_img_5 = '';
        $profile->profile_img_6 = '';
        $profile->pic_desc_1 = $req->pic_desc_1;
        $profile->pic_desc_2 = $req->pic_desc_2;
        $profile->pic_desc_3 = $req->pic_desc_3;
        $profile->pic_desc_4 = $req->pic_desc_4;
        $profile->pic_desc_5 = $req->pic_desc_5;
        $profile->pic_desc_6 = $req->pic_desc_6;
        $profile->press_release_1 = json_encode($req->press_release_1);
        $profile->press_release_2 = json_encode($req->press_release_2);
        $profile->press_release_3 = json_encode($req->press_release_3);
        $profile->dc_articles = json_encode($req->dc_articles);
        $profile->facebook = $req->facebook;
        $profile->twitter = $req->twitter;
        $profile->linkedin = $req->linkedin;
        $profile->youtube = $req->youtube;
        $profile->instagram = $req->instagram;
        $profile->is_featured = $req->is_featured;
        $profile->description = $req->description;
        $profile->home_featured = $req->home_featured;
        $profile->short_description = $req->short_description;
        $profile->metatitle = $req->metatitle;
        $profile->metakeyword = $req->metakeyword;
        $profile->metadescription = $req->metadescription;
        $profile->save();
        $allservices = $req->services;
        $profile_id = $profile->id;
        /*for ($cnt=0; $cnt < count($allservices); $cnt++) {
            $all_ser[] = [
                'company_id' => $profile_id,
                'service_id' => $allservices[$cnt]
            ];
        }
        CompanyService::insert($all_ser);*/
        if ($req->hasFile('logo')) {
            $logo = $req->file('logo');
            $original_image_size=getimagesize($logo);
            list($width, $height)=getimagesize($logo);
            $sizes_arr=["275,275","160,160"];
            $time=time();
            $image_name =$time.'.' . $logo->getClientOriginalExtension();
            // $destinationPath='images/'.$profile_id.'/profile';
            $destinationPath=public_path('images/'.$profile_id.'/profile');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $resize_image = Image::make($logo->getRealPath());
            foreach ($sizes_arr as $size_value) {
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name =$time.'-'.$new_width.'x'.$new_height.'.'.$logo->getClientOriginalExtension();
                $resize_image->resize($new_width, $new_height, function ($constraint) {
                    //$constraint->aspectRatio();
                })->save($destinationPath . '/' . $new_image_name);
            }
            $logo->move($destinationPath, $image_name);
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->logo ='images/'.$profile_id.'/profile/'.$image_name;
            $CompanyProfile->save();
        }
        if ($req->hasFile('featured_image')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->featured_image=Storage::disk('public_uploads')->put($allConfig, $req->featured_image);
            $CompanyProfile->save();
        }

        if ($req->hasFile('profile_img_1')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->profile_img_1=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_1);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_2')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->profile_img_2=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_2);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_3')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->profile_img_3=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_3);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_4')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->profile_img_4=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_4);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_5')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->profile_img_5=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_5);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_6')) {
            $allConfig ='images/' .$profile_id. '/profile';
            $CompanyProfile = CompanyProfile::find($profile_id);
            $CompanyProfile->profile_img_6=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_6);
            $CompanyProfile->save();
        }
        return redirect()->action([CompanyProfileController::class, 'index'])->with('success', 'Successfully profile created');
    }

    public function edit(Request $req)
    {
        abort_if(Gate::denies('company_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company = CompanyProfile::with('user')->get()->find($req->company);
        $company_services = CompanyService::where('company_id', '=', $req->company)->where('status', '=', '1')->where('deleted_at', '=', null)->get();
        $total_services = [];
        foreach ($company_services as $key => $value) {
            $total_services[] = $value->service_id;
        }
        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 4);
        })->active()->get();
        $services = Service::active()->get()->pluck('title', 'id');
        $country = Country::all()->pluck('name', 'id');
        $states = \App\Models\State::where('country_id', $company->country)->pluck('name', 'id');
        return view('admin.company.profile.edit', compact('company', 'services', 'total_services', 'users', 'country', 'states'));
    }

    public function update(Request $req, CompanyProfile $company)
    {
        abort_if(Gate::denies('company_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //return $req->except('logo', 'featured_image');
        $req_data = $req->except('logo', 'featured_image', 'profile_img_1', 'profile_img_2', 'profile_img_3', 'profile_img_4', 'profile_img_5', 'profile_img_6', 'press_release_1', 'press_release_2', 'press_release_3', 'dc_articles');
        $req_data['press_release_1'] = json_encode($req->press_release_1);
        $req_data['press_release_2'] = json_encode($req->press_release_2);
        $req_data['press_release_3'] = json_encode($req->press_release_3);
        $req_data['dc_articles'] = json_encode($req->dc_articles);
        $company->update($req_data);
        if($req->services){
            CompanyService::where('company_id', $company->id)->forceDelete();
            foreach($req->services as $service){
                CompanyService::Create(['company_id' => $company->id, 'service_id' => $service]);
            }
        }
        if ($req->hasFile('logo')) {
            if(File::exists(public_path($company->logo))){
                File::delete(public_path($company->logo));
            }
            $logo = $req->file('logo');
            $original_image_size=getimagesize($logo);
            list($width, $height)=getimagesize($logo);
            $sizes_arr=["275,275","160,160"];
            $time=time();
            $image_name =$time.'.' . $logo->getClientOriginalExtension();
            $destinationPath=public_path('images/'.$company->id.'/profile');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $resize_image = Image::make($logo->getRealPath());
            foreach ($sizes_arr as $size_value) {
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name =$time.'-'.$new_width.'x'.$new_height.'.'.$logo->getClientOriginalExtension();
                $resize_image->resize($new_width, $new_height, function ($constraint) {
                    //$constraint->aspectRatio();
                })->save($destinationPath . '/' . $new_image_name);
            }
            $logo->move($destinationPath, $image_name);
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->logo ='images/'.$company->id.'/profile/'.$image_name;
            $CompanyProfile->save();
        }
        if($req->hasFile('featured_image')) {
            if(File::exists(public_path($company->featured_image))){
                File::delete(public_path($company->featured_image));
            }
            $allConfig ='images/' .$company->id. '/profile'; 
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->featured_image=Storage::disk('public_uploads')->put($allConfig, $req->featured_image);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_1')) {
            if(File::exists(public_path($company->profile_img_1))){
                File::delete(public_path($company->profile_img_1));
            }
            $allConfig ='images/' .$company->id. '/profile';
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->profile_img_1=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_1);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_2')) {
            if(File::exists(public_path($company->profile_img_2))){
                File::delete(public_path($company->profile_img_2));
            }
            $allConfig ='images/' .$company->id. '/profile';
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->profile_img_2=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_2);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_3')) {
            if(File::exists(public_path($company->profile_img_3))){
                File::delete(public_path($company->profile_img_3));
            }
            $allConfig ='images/' .$company->id. '/profile';
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->profile_img_3=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_3);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_4')) {
            if(File::exists(public_path($company->profile_img_4))){
                File::delete(public_path($company->profile_img_4));
            }
            $allConfig ='images/' .$company->id. '/profile';
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->profile_img_4=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_4);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_5')) {
            if(File::exists(public_path($company->profile_img_5))){
                File::delete(public_path($company->profile_img_5));
            }
            $allConfig ='images/' .$company->id. '/profile';
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->profile_img_5=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_5);
            $CompanyProfile->save();
        }
        if ($req->hasFile('profile_img_6')) {
            if(File::exists(public_path($company->profile_img_6))){
                File::delete(public_path($company->profile_img_6));
            }
            $allConfig ='images/' .$company->id. '/profile';
            $CompanyProfile = CompanyProfile::find($company->id);
            $CompanyProfile->profile_img_6=Storage::disk('public_uploads')->put($allConfig, $req->profile_img_6);
            $CompanyProfile->save();
        }
        return redirect()->action([CompanyProfileController::class, 'index'])->with('success', 'Profile updated Successfully');
    }

    public function show(Request $req, CompanyProfile $company)
    {
        abort_if(Gate::denies('company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $country = Country::all()->pluck('name', 'id');
        $videos = CompanyVideo::where('company_id', $company->id)->latest()->get();
        $galleries =CompanyGallery::where('company_id', $company->id)->latest()->get();
        return view('admin.company.profile.show', compact('country', 'company', 'videos', 'galleries'));
    }

    public function destroy(CompanyProfile $company)
    {
        $company->delete();
        return back();
    }

    public function massDestroy(Request $request)
    {
        CompanyProfile::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massActiveStatus(Request $request)
    {
        CompanyProfile::whereIn('id', $request->ids)->update(['status' => '1']);
//
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massInActiveStatus(Request $request)
    {
        CompanyProfile::whereIn('id', $request->ids)->update(['status' => '0']);
//
        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function massCertifiedYes(Request $request)
    {
        CompanyProfile::whereIn('id', $request->ids)->update(['is_certified' => 'Yes']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massCertifiedNo(Request $request)
    {
        CompanyProfile::whereIn('id', $request->ids)->update(['is_certified' => 'No']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    
    public function massFeatureYes(Request $request)
    {
        CompanyProfile::whereIn('id', $request->ids)->update(['is_featured' => 'Yes']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massFeatureNo(Request $request)
    {
        CompanyProfile::whereIn('id', $request->ids)->update(['is_featured' => 'No']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    
    public function getSlug(Request $request)
    {
        $title = $request->title;
        if(isset($request->id)){
            $company = CompanyProfile::find($request->id);
            if($company->title != $request->title){
                $companies = CompanyProfile::where('title', $title)->get()->count();
                if($companies > 0){
                    $slug = \Str::slug($request->title.'-'.($companies+1));
                }else{
                    $slug = \Str::slug($request->title);
                }
            }else{
                $slug = $company->slug;
            }
        }else{
            $companies = CompanyProfile::where('title', $title)->get()->count();
            if($companies  > 0){
                $slug = \Str::slug($request->title.'-'.($companies+1));
            }else{
                $slug = \Str::slug($request->title);
            }
        }
        $status = ['status'=>'200','slug'=>$slug];
        return json_encode($status);
        exit();
    }
}
