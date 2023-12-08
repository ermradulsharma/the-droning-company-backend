<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\CompanyService;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Jobs\CompanyProfileImageCrop;
use App\Services\CompanyServicesService;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Storage;
use File;

class CompanyProfileBuildController extends Controller
{
    /**
    * Pilot Basic profile create
    *
    * This endpoint allows you to add a new pilot profile setup requirement to the list.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *
    * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
    *
    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
           "statusCode": 200,
           "message": "Basic profile save successfully",
           "data": {
               "pilot_profile_id": 71,
               "slug": "ivan-charles"
           }
       }
    *

    * @response status=400 {
           "statusCode": 400,
           "message": "validation error",
           "data": {
               "userId": [
                   "user id is required"
               ]
           }
       }

     * @authenticated
    */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
             // User id get from login api - required
            'user_id' => ['required','exists:users,id'],
              // The title of the profile  - required
            'title'    => ['required'],
              // The travel_option boolean value (`true`,`false`) - required
            //'travel_option' =>['required','string'],
            // The description of the profile  - required
            'description'=>['required','string'],
             // The short_description of the profile  - optional
            'short_description'=>['nullable','string'],
             // The metatitle of the profile  - optional
            'metatitle'=>['nullable','string'],
             // The metakeyword of the profile  - optional
            'metakeyword'=>['nullable','string'],
             // The metadescription of the profile  - optional
            'metadescription'=>['nullable','string'],
             // The profile_image of the profile  base64 format- optional
            'profile_image'=>['nullable','string'],
              // The license_image of the profile  base64 format- optional
            'license_image'=>['nullable','string'],
            //skills required as []
            //'services'=>['required'],
            // hourly rate of pilot - required
            //'hourly_rate'=>['nullable','numeric']
        ]);


        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$request->user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
        //$data=$request->all();
        $data = $request->except('logo', 'featured_image', 'profile_img_1', 'profile_img_2', 'profile_img_3', 'profile_img_4', 'profile_img_5', 'profile_img_6');
        $data['image']=NULL;
        $data['is_certified']='No';
        $data['is_featured']='No';

        $user=User::where('id', $request->user_id)->first();
        $data['slug']= \Str::slug($request->title);
        $data = array_map(fn($v) => $v === 'null' ? null : $v, $data);
        $profile=CompanyProfile::create($data);
        $base64_image = $request->profile_image;
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $base64_image);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $base64_image);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'pp'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);

            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->logo =$allConfig;
            $companyProfile->save();

            //update user image
            
            $userImage=User::where('id', $profile->user_id)->first();
            $userImage->profile_photo=$allConfig;
            $userImage->save();

            CompanyProfileImageCrop::dispatch($companyProfile)->delay(now()->addSeconds(3));
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->license_image)) {
            $data = substr($request->license_image, strpos($request->license_image, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->license_image);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->license_image);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'lp'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->featured_image =$allConfig;
            $companyProfile->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_1)) {
            $data = substr($request->profile_img_1, strpos($request->profile_img_1, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_1);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_1);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp1'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->profile_img_1 = $allConfig;
            $companyProfile->save();
        }
        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_2)) {
            $data = substr($request->profile_img_2, strpos($request->profile_img_2, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_2);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_2);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp2'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->profile_img_2 = $allConfig;
            $companyProfile->save();
        }
        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_3)) {
            $data = substr($request->profile_img_3, strpos($request->profile_img_3, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_3);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_3);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp3'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->profile_img_3 = $allConfig;
            $companyProfile->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_4)) {
            $data = substr($request->profile_img_4, strpos($request->profile_img_4, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_4);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_4);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp3'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->profile_img_4 = $allConfig;
            $companyProfile->save();
        }
        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_5)) {
            $data = substr($request->profile_img_5, strpos($request->profile_img_5, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_5);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_5);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp3'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->profile_img_5 = $allConfig;
            $companyProfile->save();
        }
        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_6)) {
            $data = substr($request->profile_img_6, strpos($request->profile_img_6, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_6);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_6);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp3'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $companyProfile = CompanyProfile::find($profile->id);
            $companyProfile->profile_img_6 = $allConfig;
            $companyProfile->save();
        }

        if ($request->has('services')) {
            if (is_array($request->input('services'))) {
                foreach ($request->input('services') as $key => $value) {
                    CompanyService::create([
                    'company_id' => $profile->id,
                    'service_id' => $value['id']
                ]);
                }
            } else {
                foreach (json_decode($request->input('services')) as $key => $value) {
                    CompanyService::create([
                    'company_id' => $profile->id,
                    'service_id' => $value->id
                ]);
                }
            }
        }

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'Basic profile save successfully',
            'data'=>[
                'company_profile_id'=>$profile->id,
                'slug'=>$profile->slug
            ],
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * Pilot Basic profile show
    *
    * This endpoint allows you to fetch basis profile setup.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *
    *
    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                "statusCode": 200,
                "message": "profile fetch successfully",
                "data": {
                    "pilot_profile_id": 71,
                    "user_id": 71,
                    "name": "Ivan Charles",
                    "title": "Pilot titleupdaate",
                    "slug": "ivan-charles",
                    "short_description": "breif profile introduction",
                    "profile_image": "http://local.drone/images/71/profile/1631517898.png",
                    "hourly_rate": 100,
                    "travel_option": true,
                    "metatitle": "nullable right know,usefull when ssr implement",
                    "metakeyword": "some catchy keywords",
                    "metadescription": "let search engine know you also in queue",
                    "member_since": "2021",
                    "created_at": "2021-09-13T07:23:23.000000Z",
                    "skills": [
                        {
                            "id": 1,
                            "name": "drone"
                        },
                        {
                            "id": 2,
                            "name": "Photography"
                        },
                        {
                            "id": 3,
                            "name": "Videography"
                        }
                    ]
                }
       }
    *
    *
    * @response status=400 {
           "statusCode": 400,
           'message' => 'profile not found!',
            'data' =>[]
       }

     * @authenticated
    */
    public function show(Request $request, int $user_id)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
        $profile=CompanyProfile::byUser($user_id)
                        ->latest('id')
                        ->first();


        if (!$profile) {
            return response()->json([
                 'statusCode' =>404,
                'message' => 'profile not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        $data=[
                'company_id'=>$profile->id,
                'user_id'=>$profile->user_id,
                'name'=>$profile->user->name,
                'title'=>$profile->title,
                'slug'=>$profile->slug,
                'home_location'=>$profile->home_location,
                'short_description'=>$profile->short_description,
                'description'=>$profile->description,
                'profile_image'=>asset($profile->logo),
                'license_image'=>asset($profile->featured_image),
                'logo'=>asset($profile->logo),
                'featured_image'=>asset($profile->featured_image),
                'address'=>$profile->address,
                'suite' => $profile->suite,
                'city'=>$profile->city,
                'state'=>$profile->state,
                'country'=>$profile->country,
                'zip_code'=>$profile->zip_code,
                'email'=>$profile->email,
                'phone'=>$profile->phone,
                'website'=>$profile->website,
                'working_hours'=>$profile->working_hours,
                'contact_person'=> $profile->contact_person,
                'metatitle'=>$profile->metatitle,
                'metakeyword'=>$profile->metakeyword,
                'metadescription'=>$profile->metadescription,
                'member_since'=>$profile->created_at->format('Y'),
                'created_at'=>$profile->created_at,
                'services'=>(new CompanyServicesService())->companyServicesArray($profile->id),
                'service_1'=>$profile->service_1,
                'service_2'=>$profile->service_2,
                'service_3'=>$profile->service_3,
                'pic_desc_1'=>$profile->pic_desc_1,
                'pic_desc_2'=>$profile->pic_desc_2,
                'pic_desc_3'=>$profile->pic_desc_3,
                'pic_desc_4'=>$profile->pic_desc_4,
                'pic_desc_5'=>$profile->pic_desc_5,
                'pic_desc_6'=>$profile->pic_desc_6,
                'press_release_1'=> json_decode($profile->press_release_1),
                'press_release_2'=> json_decode($profile->press_release_2),
                'press_release_3'=> json_decode($profile->press_release_3),
                'dc_articles'=> json_decode($profile->dc_articles),
                'profile_img_1'=> $profile->profile_img_1 ? asset($profile->profile_img_1) : '',
                'profile_img_2'=> $profile->profile_img_2 ? asset($profile->profile_img_2) : '',
                'profile_img_3'=> $profile->profile_img_3 ? asset($profile->profile_img_3) : '',
                'profile_img_4'=> $profile->profile_img_4 ? asset($profile->profile_img_4) : '',
                'profile_img_5'=> $profile->profile_img_5 ? asset($profile->profile_img_5) : '',
                'profile_img_6'=> $profile->profile_img_6 ? asset($profile->profile_img_6) : '',
                'facebook'=>$profile->facebook,
                'twitter'=>$profile->twitter,
                'linkedin'=>$profile->linkedin,
                'youtube'=>$profile->youtube,
                'instagram'=>$profile->instagram,
          ];
          $data = array_map(fn($v) => $v === 'null' ? null : $v, $data);
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile fetch successfully',
            'data'=>$data
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
    * Pilot Basic profile update
    *
    * This endpoint allows you to update a basic pilot profile.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *
    * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
    *
    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
           "statusCode": 200,
           'message' =>'profile update successfully',
            'data'=>[],
       }
    *

    * @response status=400 {
           "statusCode": 400,
           "message": "validation error",
           "data": {
               "userId": [
                   "user id is required"
               ]
           }
       }

    * @authenticated
    */

    public function update(Request $request, int $user_id)
    {
        $validation = Validator::make($request->all(), [
             // The title of the profile  - required
            'title'    => ['required','string'],
              // The travel_option boolean value (`true`,`false`) - required
            //'travel_option' =>['required','string'],
             // The description of the profile  - required
            'description'=>['required','string'],
             // The short_description of the profile  - optional
            'short_description'=>['nullable','string'],
             // The metatitle of the profile  - optional
            'metatitle'=>['nullable','string'],
             // The metakeyword of the profile  - optional
            'metakeyword'=>['nullable','string'],
             // The metadescription of the profile  - optional
            'metadescription'=>['nullable','string'],
             // The profile_image of the profile  base64 format- optional
            'profile_image'=>['nullable','string'],
              // The license_image of the profile  base64 format- optional
            'license_image'=>['nullable','string'],
               //skills required as []
            //'services'=>['required'],
            // hourly rate of pilot - required
            //'hourly_rate'=>['nullable','numeric']
        ]);


        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $profile=CompanyProfile::byUser($user_id)->latest('id')->first();
        // $request_data=$request->all();
        $request_data = $request->except('logo', 'featured_image', 'profile_img_1', 'profile_img_2', 'profile_img_3', 'profile_img_4', 'profile_img_5', 'profile_img_6');
        $base64_image = $request->profile_image;
        if($request->title != $profile->title){
            $request_data['slug'] = \Str::slug($request->title);
        }
        if ($request->is_new_profile_image) {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                if(File::exists(public_path($profile->logo))){
                    File::delete(public_path($profile->logo));
                }
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
                $data = base64_decode($data);
                $imgforext = explode(',', $base64_image);
                $ini = substr($imgforext[0], 11);
                $type = explode(';', $ini);
                $extension = $type[0]; // results extension
                $img = str_replace('data:image/' . $extension . ';base64,', '', $base64_image);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = 'pp'.time() . '.' . $extension;
                $allConfig ='images/company/' .$profile->id. '/profile/' . $file;
                Storage::disk('public_uploads')->put($allConfig, $data);

                $request_data['logo']=$allConfig;

                //update user image
            
                $userImage=User::where('id', $profile->user_id)->first();
                $userImage->profile_photo=$allConfig;
                $userImage->save();

                CompanyProfileImageCrop::dispatch($profile)->delay(now()->addSeconds(3));
            }
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->license_image)) {
            if(File::exists(public_path($profile->featured_image))){
                File::delete(public_path($profile->featured_image));
            }
            $data = substr($request->license_image, strpos($request->license_image, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->license_image);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->license_image);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'lp'.time() . '.' . $extension;
            $allConfig ='images/company/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);

            $request_data['featured_image']=$allConfig;
        } else {
            $request_data['featured_image']=$profile->featured_image;
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_1)) {
            if(File::exists(public_path($profile->profile_img_1))){
                File::delete(public_path($profile->profile_img_1));
            }
            $data = substr($request->profile_img_1, strpos($request->profile_img_1, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_1);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_1);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp1'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $request_data['profile_img_1'] = $allConfig;
        }else {
            $request_data['profile_img_1']=$profile->profile_img_1;
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_2)) {
            if(File::exists(public_path($profile->profile_img_2))){
                File::delete(public_path($profile->profile_img_2));
            }
            $data = substr($request->profile_img_2, strpos($request->profile_img_2, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_2);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_2);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp2'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $request_data['profile_img_2'] = $allConfig;
        }else {
            $request_data['profile_img_2']=$profile->profile_img_2;
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_3)) {
            if(File::exists(public_path($profile->profile_img_3))){
                File::delete(public_path($profile->profile_img_3));
            }
            $data = substr($request->profile_img_3, strpos($request->profile_img_3, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_3);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_3);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp3'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $request_data['profile_img_3'] = $allConfig;
        }else {
            $request_data['profile_img_3']=$profile->profile_img_3;
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_4)) {
            if(File::exists(public_path($profile->profile_img_4))){
                File::delete(public_path($profile->profile_img_4));
            }
            $allConfig = '';
            $data = substr($request->profile_img_4, strpos($request->profile_img_4, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_4);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_4);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp4'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $request_data['profile_img_4'] = $allConfig;
        }else {
            $request_data['profile_img_4']=$profile->profile_img_4;
        }
        
        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_5)) {
            if(File::exists(public_path($profile->profile_img_5))){
                File::delete(public_path($profile->profile_img_5));
            }
            $allConfig = '';
            $data = substr($request->profile_img_5, strpos($request->profile_img_5, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_5);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_5);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp5'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $request_data['profile_img_5'] = $allConfig;
        }else {
            $request_data['profile_img_5']=$profile->profile_img_5;
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->profile_img_6)) {
            if(File::exists(public_path($profile->profile_img_6))){
                File::delete(public_path($profile->profile_img_6));
            }
            $allConfig = '';
            $data = substr($request->profile_img_6, strpos($request->profile_img_6, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->profile_img_6);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->profile_img_6);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'cp6'.time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $request_data['profile_img_6'] = $allConfig;
        }else {
            $request_data['profile_img_6']=$profile->profile_img_6;
        }
        $request_data = array_map(fn($v) => $v === 'null' ? null : $v, $request_data);
        $profile->update($request_data);

        CompanyService::where('company_id', $profile->id)->forceDelete();
       
        if ($request->has('services')) {
            if (is_array($request->input('services'))) {
                foreach ($request->input('services') as $key => $value) {
                    CompanyService::create([
                    'company_id' => $profile->id,
                    'service_id' => $value->id
                ]);
                }
            } else {
                foreach (json_decode($request->input('services')) as $key => $value) {
                    CompanyService::create([
                    'company_id' => $profile->id,
                    'service_id' => $value->id
                ]);
                }
            }
        }

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile update successfully',
            'data'=>[],
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function services()
    {
        $services = \App\Models\Service::query()
            ->select('id', 'title')
            ->where('status', '1')
            ->orderBy('title', 'ASC')
            ->get();

        if ($services->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Services not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Services fetched successfully',
             'data'=>$services
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function remove(Request $request, CompanyProfile $profile)
    {
        $profile->delete();
        $profile->user->delete();
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile delete successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }

}