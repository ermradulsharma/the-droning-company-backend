<?php

namespace App\Http\Controllers\Api\V1;

use Storage;
use App\Models\PilotGallery;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use App\Jobs\GalleryImageCrop;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PilotGalleryController extends Controller
{

    /**
    * Pilot Gallery create
    *
    * This endpoint allows you to store new pilot Gallery.
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
                "message": "profile Gallery save successfully",
                "data": []
      }
    *  @authenticated
    */
    public function store(Request $request, int $user_id)
    {
        $validation = Validator::make($request->all(), [
             'gallery'=>['required'],
              //service area array of object address_1 is required
             'gallery.*.image' => 'required',
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
      
        $i=1;
        // PilotGallery::where('pilot_profile_id', $profile->id)->forceDelete();
        foreach ($request->input('gallery') as $key => $value) {
            $base64_image = $value['image'];
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
                $file = uniqid() . '.' . $extension;
                $allConfig ='images/' .$profile->id. '/gallery/' . $file;
                Storage::disk('public_uploads')->put($allConfig, $data);

                $gallery=PilotGallery::create([
                    'pilot_profile_id'=>$profile->id,
                    'image'=>$allConfig,
                    'status'=>'1'
                ]);

                GalleryImageCrop::dispatch($gallery)
                   ->delay(now()->addSeconds(3));
            }
            $i++;
        }

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile gallery save successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
    * Pilot Gallery show
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
                "message": "profile gallery fetch successfully",
                "data": [
                        {
                        "gallery_id": 231,
                        "image": "http://local.drone/images/68/gallery/613f12c409488-400x400.png"
                        },
                        {
                        "gallery_id": 232,
                        "image": "http://local.drone/images/68/gallery/613f12c464792-400x400.png"
                        },
                        {
                        "gallery_id": 233,
                        "image": "http://local.drone/images/68/gallery/613f12c47fdc5-400x400.png"
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
        $gallery=PilotGallery::where('pilot_profile_id', $pilot_profile_id)
                        ->select('id as gallery_id', 'image')
                        ->latest('id')
                        ->get();

        if ($gallery->isEmpty()) {
            return response()->json([
            'statusCode'=>404,
            'message' =>'profile gallery not found',
            'data'=>[]
            ])->setStatusCode(404);
        }
        
        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile gallery fetch successfully',
            'data'=>$gallery
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
    * Pilot Gallery delete
    *
    * This endpoint allows you to store new pilot Gallery.
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
               "message": "profile gallery image delete successfully",
               "data": []
     }
    * @authenticated
    */
    public function remove(Request $request, int $gallery_id)
    {
        PilotGallery::find($gallery_id)->forceDelete();

        return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' =>'profile gallery image delete successfully',
            'data'=>[]
        ])->setStatusCode(Response::HTTP_OK);
    }
}
