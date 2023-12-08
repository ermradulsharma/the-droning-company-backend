<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\PilotVideos;
use App\Models\PilotAddress;
use App\Models\PilotGallery;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Services\SkillService;
use App\Models\PilotEquipments;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PilotDashboardLandingController extends Controller
{

    /**
    * Pilot Dashboard
    *
    * This endpoint allows you to fetch pilot dashboard data.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *

    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                "statusCode": 200,
                "message": "profile dashboard fetch successfully",
                "email_verified_at": null,
                "data": {
                "basic_profile": {
                    "id": 75,
                    "user_id": 71,
                    "title": "Profile title",
                    "slug": "ivan-charles",
                    "image": "http://local.project1/images/75/profile/1631601078.png",
                    "description": "<p>description of profile</p>",
                    "short_description": "<p>short description of profile</p>",
                    "is_certified": "No",
                    "travel_option": "Yes",
                    "is_featured": "No",
                    "metatitle": "meta title",
                    "metakeyword": "put some google magic keywrods",
                    "metadescription": "elaborate keywrods in little bit in detail",
                    "status": "1",
                    "created_at": "2021-09-14 06:31:18",
                    "home_featured": 0,
                    "is_insured": true
                },
                "service_location": [
                    {
                        "service_id": 122,
                        "city": "Delhi",
                        "state": "Delhi",
                        "country": "India",
                        "zip_code": "110044"
                    },
                    {
                        "service_id": 121,
                        "city": "Delhi",
                        "state": "Delhi",
                        "country": "India",
                        "zip_code": "110044"
                    }
                ],
                "reel_video": [
                    {
                        "pilot_video_id": 174,
                        "video_type": "Youtube",
                        "video_url": "https://www.youtube.com/watch?v=CXa0f4-dWi4",
                        "video_key": "CXa0f4-dWi4",
                        "position": "Gallery"
                    },
                    {
                        "pilot_video_id": 173,
                        "video_type": "Youtube",
                        "video_url": "https://www.youtube.com/watch?v=CXa0f4-dWi4",
                        "video_key": "CXa0f4-dWi4",
                        "position": "Gallery"
                    }
                ],
                "equipment": [
                    {
                        "equipment_id": 154,
                        "title": "Equipment title",
                        "manufacturer": "Company name",
                        "image": "http://local.project1/images/75/equipment/1632565134.png"
                    }
                ],
                "gallery": [
                    {
                        "gallery_id": 239,
                        "image": "http://local.project1/images/75/gallery/61567a3dc360c.png"
                    },
                    {
                        "gallery_id": 238,
                        "image": "http://local.project1/images/75/gallery/61567a3da99a5.png"
                    }
                ]
            }
      }
    *  @ authenticated
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

        $profile=PilotProfile::byUser($user_id)
                            ->latest('id')
                            ->first();





                            
        if (!$profile) {
            return response()->json([
                'statusCode'=>404,
                'message' =>'pilot not created',
                'email_verified_at'=>$user->email_verified_at,
                'data'=>[]
            ])->setStatusCode(404);
        }

        $data=[
                'pilot_profile_id'=>$profile->id,
                'user_id'=>$profile->user_id,
                'name'=>$profile->users->name,
                'email'=>$profile->users->email,
                'mobile'=>$profile->users->mobile,
                'title'=>$profile->title,
                'slug'=>$profile->slug,
                'short_description'=>$profile->short_description,
                'description'=>$profile->description,
                'profile_image'=>asset($profile->image),
                'license_image'=>$profile->license_image,
                'travel_option'=>$profile->travel_option,
                'metatitle'=>$profile->metatitle,
                'metakeyword'=>$profile->metakeyword,
                'metadescription'=>$profile->metadescription,
                'member_since'=>$profile->created_at->format('Y'),
                'created_at'=>$profile->created_at,
                'is_insured'=>$profile->is_insured,
                'is_certified'=>$profile->is_certified,
                'skills'=>(new SkillService())->pilotSkillsArray($profile->id),

          ];

        $address=PilotAddress::where('pilot_profile_id', $profile->id)
                        ->latest('id')
                        ->take(2)
                        ->get();


        $address_data=[];
        foreach ($address as $key => $value) {
            $address_data[]=[
                'service_id'=>$value->id,
                'city'=>$value->city,
                'state'=>$value->stateRelation->name ?? '',
                'country'=>$value->countryRelation->name ?? '',
                'zip_code'=>$value->zip
            ];
        }
        
        $videos=PilotVideos::where('pilot_profile_id', $profile->id)
                        ->select('id as pilot_video_id', 'type as video_type', 'video as video_url', 'video_key', 'position')
                        ->latest('id')
                        ->take(2)
                        ->get();

        $equipments=PilotEquipments::where('pilot_profile_id', $profile->id)
                        ->select('id as equipment_id', 'title', 'manufacturer', 'image')
                        ->latest('id')
                        ->take(2)
                        ->get();


        $gallery=PilotGallery::where('pilot_profile_id', $profile->id)
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
                'service_location'=>$address_data,
                'reel_video'=>$videos,
                'equipment'=>$equipments,
                'gallery'=>$gallery,
            ]
        ])->setStatusCode(Response::HTTP_OK);
    }
}
