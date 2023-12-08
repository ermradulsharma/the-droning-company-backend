<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Collection;

class CountryApiController extends Controller
{
    /**
     * Country Api .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Country not found!
     *
     *
     * <aside class="notice">basepath/api/v1/country.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Country fetch successfully",
            "data": [
                {
                    "id": 244,
                    "name": "Aaland Islands"
                },
                {
                    "id": 1,
                    "name": "Afghanistan"
                },
                {
                    "id": 2,
                    "name": "Albania"
                },
                {
                    "id": 3,
                    "name": "Algeria"
                },
                {
                    "id": 4,
                    "name": "American Samoa"
                }
            ]
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "Country not found!",
            "data": []
        }
     *
     *
     *
     */

    public function index()
    {
        $country = Country::query()->select('id', 'name')
            ->orderBy('name', 'ASC')->get();

        if ($country->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Country not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Country fetch successfully',
             'data'=>$country
        ])->setStatusCode(Response::HTTP_OK);
    }
}
