<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\CompanyVideo;
use App\Models\CompanyGallery;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Services\CompanyServicesService;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CompanyDashboardLandingController extends Controller
{

    /**
    * Company Dashboard
    */
    public function __invoke(Request $request, int $user_id)
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

        $user=User::find($user_id);

        if (!$user) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'pilot dashboard not found',
            'data'=>[]
        ])->setStatusCode(404);
        }

        $profile=CompanyProfile::byUser($user_id)->latest('id')->first();
        if (!$profile) {
            return response()->json([
                'statusCode'=>404,
                'message' =>'pilot not created',
                'email_verified_at'=>$user->email_verified_at,
                'data'=>[]
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
            'license_image'=>$profile->featured_image,
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
            // 'press_release_1'=>$profile->press_release_1,
            // 'press_release_2'=>$profile->press_release_2,
            // 'press_release_3'=>$profile->press_release_3,
            'press_release_1'=> json_decode($profile->press_release_1),
            'press_release_2'=> json_decode($profile->press_release_2),
            'press_release_3'=> json_decode($profile->press_release_3),
            'dc_articles'=> json_decode($profile->dc_articles),
            'profile_img_1'=>asset($profile->profile_img_1),
            'profile_img_2'=>asset($profile->profile_img_2),
            'profile_img_3'=>asset($profile->profile_img_3),
            'profile_img_4'=>asset($profile->profile_img_4),
            'profile_img_5'=>asset($profile->profile_img_5),
            'profile_img_6'=>asset($profile->profile_img_6),
            'facebook'=>$profile->facebook,
            'twitter'=>$profile->twitter,
            'linkedin'=>$profile->linkedin,
            'youtube'=>$profile->youtube,
            'instagram'=>$profile->instagram,
          ];
      
        $videos=CompanyVideo::where('company_id', $profile->id)
                        ->select('id as pilot_video_id', 'type as video_type', 'video as video_url', 'video_key', 'position')
                        ->latest('id')
                        ->take(2)
                        ->get();

        $gallery=CompanyGallery::where('company_id', $profile->id)
                        ->select('id as gallery_id', 'image')
                        ->latest('id')
                        ->take(2)
                        ->get();


        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile dashboard fetch successfully',
            'email_verified_at'=>$user->email_verified_at,
            'data'=>[
                'basic_profile'=>$data,
                'reel_video'=>$videos,
                'gallery'=>$gallery,
            ]
        ])->setStatusCode(Response::HTTP_OK);
    }
}
