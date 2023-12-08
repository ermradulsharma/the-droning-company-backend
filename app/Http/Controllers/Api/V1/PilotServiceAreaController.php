<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\State;
use App\Models\Country;
use App\Models\PilotAddress;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class PilotServiceAreaController extends Controller
{
    /**
    * Pilot service area store
    *
    * This endpoint allows you to store pilot profile service area.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *
    * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
    *
    *
    * <aside class="info">Service Area data format </aside>
    *   { "service_area": [{
                        "address_1": "pilot address 1",
                        "address_2": "pilot address 2",
                        "state": "delhi",
                        "city": "delhi",
                        "country": "India",
                        "zip_code": 110044
                    }, {
                        "address_1": "address 2",
                        "address_2": "pilot address 2",
                        "state": "delhi",
                        "city": "delhi",
                        "country": "India",
                        "zip_code": 110044
                    }]
        }
    *
    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                "statusCode": 200,
                "message": "profile service area fetch successfully",
                "data": [
                    {
                    "address_1": "pilot address 1",
                    "address_2": "pilot address 2",
                    "city": "Delhi",
                    "state": "pilot address 1",
                    "country": "pilot address 2",
                    "zip_code": "110044"
                    },
                    {
                    "address_1": "address 2",
                    "address_2": "pilot address 2",
                    "city": "Delhi",
                    "state": "address 2",
                    "country": "pilot address 2",
                    "zip_code": "110044"
                    }
                    ]
      }
    *

    * @response status=400 {
          "statusCode": 400,
          "message": "validation error",
          "data": {
              "service_area.1.address_1": [
                  "address_1 is required"
              ]
          }
      }

    * @authenticated
    */

    public function store(Request $request, int $user_id)
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
        $validation = Validator::make($request->all(), [
            //service area array required
             'service_area' => ['required'],
               //service area array of object city is required
             'service_area.*.city' => 'required',
        ]);


        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $profile=PilotProfile::byUser($user_id)->latest('id')->first();

        if (!$profile) {
            return response()->json([
                'statusCode' =>401,
                'message' =>'Please build your profile first',
                'data' =>[],
            ])->setStatusCode(401);
        }


        if ($request->has('service_area')) {
            try {
                foreach ($request->input('service_area') as $key => $value) {
                    $country=Country::updateOrCreate(
                        ['name'=>mb_convert_encoding($value['country'], 'UTF-8', 'UTF-8')
                        ],
                        [
                    'name'=> mb_convert_encoding($value['country'], 'UTF-8', 'UTF-8')
                    ]
                    );

                    $state=State::updateOrCreate(
                        ['country_id'=>$country->id,
                        'name'=> mb_convert_encoding($value['state'], 'UTF-8', 'UTF-8')
                                    ],
                        ['country_id'=>$country->id,
                                    'name'=> mb_convert_encoding($value['state'], 'UTF-8', 'UTF-8'),
                                    'code'=>substr(mb_convert_encoding($value['state'], 'UTF-8', 'UTF-8'), 0, 2)
                                ]
                    );
                    $pilotAddress = new PilotAddress();
                    $pilotAddress->pilot_profile_id=$profile->id;
                    $pilotAddress->city= mb_convert_encoding($value['city'], 'UTF-8', 'UTF-8');
                    $pilotAddress->state=$state->id;
                    $pilotAddress->country=$country->id;
                    $pilotAddress->zip=$value['zip_code'];
                    $pilotAddress->save();

                    $newGeo=PilotAddress::find($pilotAddress->id);

                    $geoExist=PilotAddress::where('city', $pilotAddress->city)
                                    ->whereNotIn('id', [$pilotAddress->id])
                                    ->whereNotNull('latitude')
                                    ->whereNotNull('longitude')
                                    ->first();

                    if ($geoExist) {
                        $latitude=$geoExist->latitude;
                        $longitude=$geoExist->longitude;
                    } else {
                        [$latitude,$longitude]=$newGeo->getLatitudeAndLongitude();
                    }
                 
                    $newGeo->latitude=$latitude;
                    $newGeo->longitude=$longitude;
                    $newGeo->save();
                }
            } catch (\Exception $e) {
                return response()->json([
                         'statusCode'=>500,
                         'message' =>$e->getMessage(),
                         'data'=>$e->getMessage(),
                 ])->setStatusCode(500);
            }
        }
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile service area save successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
    * Pilot service area show
    *
    * This endpoint allows you to fetch pilot profile service area.
    *
    * If everything is okay, you'll get a `200` OK response with data.
    *
    * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
    *
    *

    *
    * @param \Illuminate\Http\Request  $request
    * @param $id required
    * @return \Illuminate\Http\Response
    *
    * @response status=200  {
                    "statusCode": 200,
                    "message": "profile service area fetch successfully",
                    "data": [
                            {
                            "address_1": "pilot address 1",
                            "address_2": "pilot address 2",
                            "city": "Delhi",
                            "state": "pilot address 1",
                            "country": "pilot address 2",
                            "zip_code": "110044"
                            },
                            {
                            "address_1": "address 2",
                            "address_2": "pilot address 2",
                            "city": "Delhi",
                            "state": "address 2",
                            "country": "pilot address 2",
                            "zip_code": "110044"
                            }
                    ]
      }
    *

    * @response status=404 {
          "statusCode": 404,
          "message": "profile service area not found",
          "data": []
      }
    * @authenticated
    */

    public function show(Request $request, int $user_id)
    {
        $profile=PilotProfile::byUser($user_id)->latest('id')->first();

        $pilot_profile_id=$profile->id ?? '';
        $address=PilotAddress::where('pilot_profile_id', $pilot_profile_id)
            ->latest('id')
            ->get();

        if ($address->isEmpty()) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'profile service area not found',
            'data'=>[]
            ])->setStatusCode(404);
        }
        

        $data=[];
        foreach ($address as $key => $value) {
            $data[]=[
                'service_id'=>$value->id,
                'city'=>$value->city,
                'state'=>$value->stateRelation->name ?? '',
                'country'=>$value->countryRelation->name ?? '',
                'zip_code'=>$value->zip
            ];
        }

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile service area fetch successfully',
            'data'=>$data
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    *  Pilot Service Area delete
    *
    * This endpoint allows you to remove pilot Equipment.
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
             "message": "Pilot service area delete successfully",
             "data": []
    }
    * @authenticated
    */

    public function deleteServiceArea(int $service_id)
    {
        $address=PilotAddress::where('id', $service_id)->first();

        


        if (!$address) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'profile service id not found',
            'data'=>[]
        ])->setStatusCode(404);
        }

        if ($address) {
            $address->forceDelete();
        }
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile service area delete successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }
}
