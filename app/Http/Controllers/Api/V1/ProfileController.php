<?php
namespace App\Http\Controllers\Api\V1;

use Storage;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\PilotProfile;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use File;

class ProfileController extends Controller
{
    
    /**
     * Profile Update
     *
     * If everything is okay, you'll get a `200` OK response Successfully Password reset.
     *
     * Otherwise, the request will fail with a `404` error, and a response You Are Not Autheticated To Forgot Password! or `400` New Password is required
     *
     *
     * <aside class="notice">basepath/api/v1/forgot-password.</aside>
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "profile update successfully"
            data :[]
        }
     *
     *
     *
        {
            "statusCode": 400,
            "message": "address_1 is required",
            "data": {
                "address_1": [
                    "address_1 is required"
                ]
            }
        }
     *
     *
     *
     */

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // new password required
            'first_name'    => ['required'],
            // old password required
            'last_name'    => ['required'],
            // base64 format
            'profile_image'=>['nullable'],
        
             // address_1 required
            'address_1'    => ['required'],
             // address_2 nullable
            'address_2'    => ['nullable'],
              // country required
            'country'    => ['required'],
              // state required
            'state'    => ['required'],
              // city nullable
            'city'    => ['nullable'],
            //mobile nullable
            'mobile'    => ['nullable'],
              // zip_code required
            'zip_code'    => ['required'],
              // hear_about_us required
            'hear_about_us'=>['required'],
            // user id from login
            'user_id' => ['required','exists:users,id','numeric'],
        ], [
            'new_password.required'=>'New Password is required',
            'old_password.required'=>'New Password is required',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $user= User::where('id', $request->user_id)->first();
        $user->first_name=$request->first_name;
        $user->last_name=$request->last_name;
        $user->mobile=$request->mobile;
        $user->hear_about_us=$request->hear_about_us;
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
            $allConfig ='images/profile' . '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);

            $user->profile_photo= $allConfig;

            //update
            $has_profile=PilotProfile::where('user_id', $request->user_id)->exists();

            if ($user->is_pilot && $has_profile) {
                $profile=PilotProfile::where('user_id', $request->user_id)
                            ->first();

                $profile->image=$allConfig;

                $profile->save();
            }
        }
       
        $user->save();

        $address=UserAddress::updateOrCreate(['user_id'=>$request->user_id], [
                    'address_1'=>ucfirst($request->address_1),
                    'address_2'=>ucfirst($request->address_2),
                    'country'=>$request->country,
                    'state'=>$request->state,
                    'zip_code'=>$request->zip_code,
                    'city'=>$request->city,
        ]);
       
       

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'profile update successfully',
                 'data' =>[]
            ])->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Profile show
     *
     * If everything is okay, you'll get a `200` OK response Successfully fetch profile detail.
     *
     *
     *
     * <aside class="notice">basepath/api/v1/forgot-password.</aside>
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *
     * @response
     *  {
                    "statusCode": 200,
                    "message": "profile fetch successfully",
                    "data": [
                            {
                            "user_id": 6,
                            "first_name": "asdasdas",
                            "last_name": "saif",
                            "profile_image": "http://local.drone/images/profile/1630210016.png",
                            "email": "s1w@332df3sdf.com1",
                            "hear_about_us": null,
                            "address_1": "Put address_1",
                            "address_2": "Put address_2",
                            "country": "India",
                            "state": "delhi",
                            "city": "new delhi",
                            "zip_code": "11005"
                            }
                            ]
        }
     *
     *
     *
     */

    public function show(Request $request, int $user_id)
    {
        $user= User::where('id', $user_id)->first();

        if (!empty($user->profile_photo)) {
            $profile_img=$user->profile_photo;
        } else {
            $profile_img=PilotProfile::hasPilotImageExist($user->id);
        }
        
        $address=UserAddress::where('user_id', $user_id)->first();
        $data=[
            'user_id'=>$user->id,
            'first_name'=>$user->first_name,
            'last_name'=>$user->last_name,
            'mobile'=>$user->mobile,
            'profile_image'=>$profile_img,
            'email'=>$user->email,
            'hear_about_us'=>$user->hear_about_us,
            'address_1'=>$address->address_1 ?? '',
            'address_2'=>$address->address_2 ?? '',
            'country'=>$address->country ?? '',
            'state'=>$address->state ?? '',
            'city'=>$address->city ?? '',
            'zip_code'=>$address->zip_code ?? '',
        ];
       

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'profile fetch successfully',
                 'data' =>$data
            ])->setStatusCode(Response::HTTP_OK);
    }



    public function deactive($user_id)
    {
        User::where('id', $user_id)->update(['active_status'=>'0']);

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'profile deactive successfully',
                 'data' =>''
            ])->setStatusCode(Response::HTTP_OK);
    }





    public function removePicture(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'image_type'    => ['required'],
            'user_id' => ['required','exists:users,id','numeric'],
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if($request->image_type == 0){
            PilotProfile::where('user_id', $request->user_id)->update(['image'=>NULL]);
        }elseif($request->image_type == 1){
            PilotProfile::where('user_id', $request->user_id)->update(['license_image'=>NULL]);
        }elseif($request->image_type == 2){
            CompanyProfile::where('user_id', $request->user_id)->update(['logo'=>NULL]);
        }elseif($request->image_type == 3){
            CompanyProfile::where('user_id', $request->user_id)->update(['featured_image'=>NULL]);

        }elseif($request->image_type == 41){
            $profile = CompanyProfile::where('user_id', $request->user_id);
            $pd = $profile->first();
            if(File::exists(public_path($pd->profile_img_1))){File::delete(public_path($pd->profile_img_1));}
            $profile->update(['profile_img_1' => NULL, 'pic_desc_1' => NULL]);
        }elseif($request->image_type == 42){
            $profile = CompanyProfile::where('user_id', $request->user_id);
            $pd = $profile->first();
            if(File::exists(public_path($pd->profile_img_2))){File::delete(public_path($pd->profile_img_2));}
            $profile->update(['profile_img_2' => NULL, 'pic_desc_2' => NULL]);
        }elseif($request->image_type == 43){
            $profile = CompanyProfile::where('user_id', $request->user_id);
            $pd = $profile->first();
            if(File::exists(public_path($pd->profile_img_3))){File::delete(public_path($pd->profile_img_3));}
            $profile->update(['profile_img_3' => NULL, 'pic_desc_3' => NULL]);
        }elseif($request->image_type == 44){
            $profile = CompanyProfile::where('user_id', $request->user_id);
            $pd = $profile->first();
            if(File::exists(public_path($pd->profile_img_4))){File::delete(public_path($pd->profile_img_4));}
            $profile->update(['profile_img_4' => NULL, 'pic_desc_4' => NULL]);
        }elseif($request->image_type == 45){
            $profile = CompanyProfile::where('user_id', $request->user_id);
            $pd = $profile->first();
            if(File::exists(public_path($pd->profile_img_5))){File::delete(public_path($pd->profile_img_5));}
            $profile->update(['profile_img_5' => NULL, 'pic_desc_5' => NULL]);
        }elseif($request->image_type == 46){
            $profile = CompanyProfile::where('user_id', $request->user_id);
            $pd = $profile->first();
            if(File::exists(public_path($pd->profile_img_6))){File::delete(public_path($pd->profile_img_6));}
            $profile->update(['profile_img_6' => NULL, 'pic_desc_6' => NULL]);
        }
        

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'remove picture successfully',
                 'data' =>''
            ])->setStatusCode(Response::HTTP_OK);
    }



}
