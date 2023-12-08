<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GearReviews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class GearReviewsApiController extends Controller
{
    /**
     * Gear Reviews .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Gear reviews not found!
     *
     *
     * <aside class="notice">basepath/api/v1/gear-reviews.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Gear reviews fetch successfully",
            "data": [
                {
                    "name": "Nell Ortiz",
                    "video": "https://www.youtube.com/watch?v=TYaNfLLOLNY",
                    "video_key": "TYaNfLLOLNY"
                },
                {
                    "name": "Paula Melton",
                    "video": "https://www.youtube.com/watch?v=65414321654",
                    "video_key": "65414321654"
                },
                {
                    "name": "Quin Garrison",
                    "video": "https://www.youtube.com/watch?v=9nsZmvkRfj4",
                    "video_key": "9nsZmvkRfj4"
                }
            ]
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Gear reviews not found!",
            "data": []
       }
     *
     *
     *
     */


    public function index()
    {
        $gear = GearReviews::query()
            ->select('name', 'video', 'video_key')
            ->where('status', '1')
            ->orderBy('id', 'DESC')
            ->take(3)
            ->get();

        if ($gear->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Gear reviews not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Gear reviews fetch successfully',
             'data'=>$gear
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
         * Gear Reviews .
         * If everything is okay, you'll get a `200` OK response with data.
         *
         * Otherwise, the request will fail with a `404` error, and a response Gear reviews not found!
         *
         *
         * <aside class="notice">basepath/api/v1/gear-reviews-all.</aside>
         *
         *
         * @return \Illuminate\Http\Response
         *
         * @response
         *  {
                "statusCode": 200,
                "message": "Gear reviews fetch successfully",
                "post_count": 3
                "data": {
                    "current_page": 1,
                    "data": [
                        {
                            "name": "Nell Ortiz",
                            "video": "https://www.youtube.com/watch?v=TYaNfLLOLNY",
                            "video_key": "TYaNfLLOLNY"
                        },
                        {
                            "name": "Paula Melton",
                            "video": "https://www.youtube.com/watch?v=65414321654",
                            "video_key": "65414321654"
                        },
                        {
                            "name": "Quin Garrison",
                            "video": "https://www.youtube.com/watch?v=9nsZmvkRfj4",
                            "video_key": "9nsZmvkRfj4"
                        }
                    ],
                    "first_page_url": "http://127.0.0.1:8000/api/v1/gear-reviews-all?page=1",
                    "from": 1,
                    "next_page_url": null,
                    "path": "http://127.0.0.1:8000/api/v1/gear-reviews-all",
                    "per_page": 10,
                    "prev_page_url": null,
                    "to": 3
                }
            }
         *
         *
         * @response status=404 {
                "statusCode": 404,
                "message": "Gear reviews not found!",
                "data": []
           }
         *
         *
         *
         */

    public function allGearReview()
    {
        $gears = GearReviews::query()
            //->select('name', 'video', 'video_key')
            ->where('status', '1')
            ->orderBy('id', 'DESC')
            ->get();

        if ($gears->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Gear reviews not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Gear reviews fetch successfully',
             'data'=>$gears
        ])->setStatusCode(Response::HTTP_OK);
    }
}
