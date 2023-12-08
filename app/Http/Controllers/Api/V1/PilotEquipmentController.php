<?php

namespace App\Http\Controllers\Api\V1;

use Storage;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Models\PilotEquipments;
use App\Jobs\EquipmentImageCrop;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class PilotEquipmentController extends Controller
{
    /**
    * Pilot Equipment create
    *
    * This endpoint allows you to store new pilot equipment.
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
                "message": "profile equipment save successfully",
                "data": []
      }
    *
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
             'title' => ['required'],
             'manufacturer' => 'required',
             'image'=>['nullable']
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
        $pilotEqp=new PilotEquipments();
        $pilotEqp->pilot_profile_id= $profile->id;
        $pilotEqp->title = $request->title;
        $pilotEqp->image = 'NULL';
        $pilotEqp->manufacturer = $request->manufacturer;

        $base64_image = $request->image;
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
            $allConfig ='images/' .$profile->id. '/equipment/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);

            $pilotEqp->image =$allConfig;

            EquipmentImageCrop::dispatch($pilotEqp)
                    ->delay(now()->addSeconds(30));
        }

        $pilotEqp->save();
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile equipment save successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * Pilot Equipment show
    *
    * This endpoint allows you to fetch pilot profile Equipment.
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
                "message": "profile equipment fetch successfully",
                "data": [
                        {
                        "equipment_id": 147,
                        "title": "Equipment title",
                        "manufacturer": "Company name",
                        "image": "http://local.drone/images/68/equipment/1631514032-400x400.png"
                        },
                        {
                        "equipment_id": 148,
                        "title": "Equipment title",
                        "manufacturer": "Company name",
                        "image": "http://local.drone/pilotNoImage.png"
                        }
                ]
      }
    *

    * @response status=4004 {
          "statusCode": 404,
          "message": "profile Equipment not found",
          "data": []
      }

     * @authenticated
    */
    public function show(Request $request, int $user_id)
    {
        $profile=PilotProfile::byUser($user_id)->latest('id')->first();

        $pilot_profile_id=$profile->id ?? '';
        $equipments=PilotEquipments::where('pilot_profile_id', $pilot_profile_id)
                        ->select('id as equipment_id', 'title', 'manufacturer', 'image')
                        ->latest('id')
                        ->get();

        if ($equipments->isEmpty()) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'profile equipment not found',
            'data'=>[]
            ])->setStatusCode(404);
        }
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile equipment fetch successfully',
            'data'=>$equipments
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * Pilot Equipment update
    *
    * This endpoint allows you to store new pilot equipment.
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
                "message": "profile equipment update successfully",
                "data": []
      }
    *  @authenticated
    */
    public function update(Request $request, int $user_id)
    {
        $validation = Validator::make($request->all(), [
             'title' => ['required'],
             'manufacturer' => 'required',
             'image'=>['nullable'],
             'equipment_id'=>['required'],
             'is_new_image'=>['required','boolean']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $profile=PilotProfile::byUser($user_id)->latest('id')->first();
        $pilotEqp=PilotEquipments::find($request->equipment_id);
        $pilotEqp->title = $request->title;
        $pilotEqp->manufacturer = $request->manufacturer;

        $base64_image = $request->image;

        if ($request->is_new_image) {
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
                $allConfig ='images/' .$profile->id. '/equipment/' . $file;
                Storage::disk('public_uploads')->put($allConfig, $data);

                $pilotEqp->image =$allConfig;

                EquipmentImageCrop::dispatch($pilotEqp)
                    ->delay(now()->addSeconds(30));
            }
        }

        $pilotEqp->save();
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile equipment update successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
    *  Pilot Equipment delete
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
              "message": "Pilot Equipment delete successfully",
              "data": []
    }
    * @authenticated
    */
    public function remove(Request $request, int $equipment_id)
    {
        $euipment=PilotEquipments::find($equipment_id);

        if (!$euipment) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'Invalid pilot equipment id',
            'data'=>[]
        ])->setStatusCode(404);
        }
        $euipment->forceDelete();

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'Pilot Equipment delete successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }
}
