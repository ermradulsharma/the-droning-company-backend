<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PilotAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Collection;

class CityApiController extends Controller
{
    /**
     * Get All Distinct City .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response City not found!
     *
     *
     * <aside class="notice">basepath/api/v1/states.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "City fetch successfully",
            "data": [
                {
                    "city": "Magnam in ad ut null"
                },
                {
                    "city": "chhapra"
                },
                {
                    "city": "Iste omnis irure in"
                },
                {
                    "city": "Ullam impedit ea au"
                },
                {
                    "city": "Incididunt laborum"
                },
                {
                    "city": "Ullam omnis pariatur"
                }
            ]
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "City not found!",
            "data": []
        }
     *
     *
     *
     */

    public function index()
    {
        $city = PilotAddress::query()->select('city')->distinct()
                ->orderBy('city', 'ASC')->get();

        if ($city->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'City not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'City fetch successfully',
             'data'=>$city
        ])->setStatusCode(Response::HTTP_OK);
    }
}
