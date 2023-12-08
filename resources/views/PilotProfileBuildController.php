<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\PilotSkills;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Jobs\ProfileImageCrop;
use App\Services\SkillService;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Storage;

class PilotProfileBuildController extends Controller
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
           "stausCode": 200,
           "message": "Basic profile save successfully",
           "data": {
               "pilot_profile_id": 71,
               "slug": "ivan-charles"
           }
       }
    *

    * @response status=400 {
           "stausCode": 400,
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
            'travel_option' =>['required','string'],
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
            //skills required as []
            'skills'=>['required'],
            // hourly rate of pilot - required
            'hourly_rate'=>['nullable','numeric']
        ]);


        if ($validation->fails()) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$request->user_id) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
        $data=$request->all();
        $data['image']='NULL';
        $data['is_certified']='No';
        $data['is_featured']='No';

        $user=User::where('id', $request->user_id)->first();

        $slug=\Str::slug($user->name);

        $data['slug']=\Str::slug($user->name);
        \Log::info($data);
        $profile=PilotProfile::create($data);


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
            $file = time() . '.' . $extension;
            $allConfig ='images/' .$profile->id. '/profile/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);

            $pilotProfile = PilotProfile::find($profile->id);
            $pilotProfile->image =$allConfig;
            $pilotProfile->save();

            ProfileImageCrop::dispatch($pilotProfile)
                    ->delay(now()->addSeconds(3));
        }

        if ($request->has('skills')) {
			foreach (json_decode($request->input('skills')) as $key => $value) {
                PilotSkills::create([
                    'pilot_profile_id' => $profile->id,
                    'skill_id' => $value->id
                ]);
            }
        }

        $profile->hourlyRate()
                    ->create([
                        'rate'=>$request->hourly_rate,
                    ]);

        return response()->json([
            'stausCode'=>Response::HTTP_OK,
            'message' =>'Basic profile save successfully',
            'data'=>[
                'pilot_profile_id'=>$profile->id,
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
                "stausCode": 200,
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
           "stausCode": 400,
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
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$user_id) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
        $profile=PilotProfile::byUser($user_id)
                        ->latest('id')
                        ->first();


        if (!$profile) {
            return response()->json([
                 'stausCode' =>404,
                'message' => 'profile not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }



        $data=[
                'pilot_profile_id'=>$profile->id,
                'user_id'=>$profile->user_id,
                'name'=>$profile->users->name,
                'title'=>$profile->title,
                'slug'=>$profile->slug,
                'short_description'=>$profile->short_description,
				'description'=>$profile->description,
                'profile_image'=>asset($profile->image),
                'hourly_rate'=>$profile->hourlyRate->rate ?? '0',
                'travel_option'=>$profile->stringToBoolean($profile->travel_option),
                'metatitle'=>$profile->metatitle,
                'metakeyword'=>$profile->metakeyword,
                'metadescription'=>$profile->metadescription,
                'member_since'=>$profile->created_at->format('Y'),
                'created_at'=>$profile->created_at,
                'skills'=>(new SkillService())->pilotSkillsArray($profile->id),
          ];


        return response()->json([
            'stausCode'=>Response::HTTP_OK,
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
           "stausCode": 200,
           'message' =>'profile update successfully',
            'data'=>[],
       }
    *

    * @response status=400 {
           "stausCode": 400,
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
            'travel_option' =>['required','string'],
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
             // The is_new_profile_image boolean (`true`,`false`)- optional
           // 'is_new_profile_image' =>['required','boolean'],
               //skills required as []
            'skills'=>['required'],
            // hourly rate of pilot - required
            'hourly_rate'=>['nullable','numeric']
        ]);


        if ($validation->fails()) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'bearerToken is required',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id!=$user_id) {
            return response()->json([
                'stausCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid bearerToken',
                'data' =>[],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $profile=PilotProfile::byUser($user_id)
                        ->latest('id')
                        ->first();
        $request_data=$request->all();
        $base64_image = $request->profile_image;

        if ($request->is_new_profile_image) {
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
                $file = time() . '.' . $extension;
                $allConfig ='images/' .$profile->id. '/profile/' . $file;
                Storage::disk('public_uploads')->put($allConfig, $data);

                $request_data['image']=$allConfig;

                ProfileImageCrop::dispatch($profile)
                    ->delay(now()->addSeconds(3));
            }
        }

        

        $profile->update($request_data);

        PilotSkills::where('pilot_profile_id', $profile->id)->delete();
        //todo
        if ($request->has('skills')) {
			foreach (json_decode($request->input('skills')) as $key => $value){
                PilotSkills::create([
                    'pilot_profile_id' => $profile->id,
                    'skill_id' => $value->id
                ]);
            }
        }

        $profile->hourlyRate()
                    ->updateOrCreate([
                        'rate'=>$request->hourly_rate,
                        'pilot_profile_id'=>$profile->id,
                    ], [
                        'rate'=>$request->hourly_rate
                    ]);

        return response()->json([
            'stausCode'=>Response::HTTP_OK,
            'message' =>'profile update successfully',
            'data'=>[],
        ])->setStatusCode(Response::HTTP_OK);
    }
}
