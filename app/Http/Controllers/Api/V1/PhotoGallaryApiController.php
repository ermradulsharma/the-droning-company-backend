<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PhotoGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;

class PhotoGallaryApiController extends Controller
{
    /**
     * Photo Gallery .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Photo Gallery not found!
     *
     *
     * <aside class="notice">basepath/api/v1/photos.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Photo Gallery fetch successfully",
            "data": [
                {
                    "id": 3,
                    "image": "http://local.drone/images/photo_gallery/3k8791nkN6kLPc1Gi0Jkrzkkn8FKFuhy52cg4vO4.png"
                },
                {
                    "id": 2,
                    "image": "http://local.drone/images/photo_gallery/iy1mHbuymOzFzpintRtNBRxt0p30j7PANPb1PIDb.png"
                },
                {
                    "id": 1,
                    "image": "http://local.drone/images/photo_gallery/McDYCXQCSkR9YMyGB5AxDSldMxWobIi9SEb5M4b5.png"
                }
            ]
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Photo Gallery not found!",
            "data": []
       }
     *
     *
     *
     */


    public function index()
    {
        $gallary = PhotoGallery::query()
                    ->select('id', 'image', 'image_text', 'image_link')
                    ->where('status', '1')
                    ->orderBy('id', 'DESC')
                    ->get();

    
        
        if ($gallary->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Photo Gallery not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Photo Gallery fetch successfully',
             'data'=>$gallary,
        ])->setStatusCode(Response::HTTP_OK);
    }


    public function new()
    {
        session()->forget('gallary_session_value');
        $gallary = PhotoGallery::query()
                    ->select('id', 'image', 'image_text', 'image_link')
                    ->where('status', '1')
                    ->orderBy('id', 'DESC')
                    ->chunk(3, function ($q) {
                        session()->push('gallary_session_value', $q);
                    });
       
        
        if (!session()->has('gallary_session_value')) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Photo Gallery not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Photo Gallery fetch successfully',
             'data'=>session()->get('gallary_session_value'),
        ])->setStatusCode(Response::HTTP_OK);
    }
}
