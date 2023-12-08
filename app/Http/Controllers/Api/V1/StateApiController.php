<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Collection;

class StateApiController extends Controller
{
    /**
     * States .
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response States not found!
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
            "message": "States fetch successfully",
            "data": [
                {
                    "id": 1,
                    "name": "Badakhshan"
                },
                {
                    "id": 2,
                    "name": "Badghis"
                },
        }
     *
     *
     * @response status=404 {
            "statusCode": 404,
            "message": "States not found!",
            "data": []
        }
     *
     *
     *
     */

    public function index()
    {
        $states = State::query()->select('id', 'name')->get();

        if ($states->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'States not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'States fetch successfully',
             'data'=>$states
        ])->setStatusCode(Response::HTTP_OK);
    }
}
