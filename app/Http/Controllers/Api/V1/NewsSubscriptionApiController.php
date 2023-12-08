<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\NewsSubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class NewsSubscriptionApiController extends Controller
{
    /**
     * News Subscription.
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response Required Field missing!
     *
     *
     * <aside class="notice">basepath/api/v1/news-subscribe.</aside>
     *
     * @bodyParam email string required Example: test@domain.com
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Successfully news subscribed",
            "data": {
                "email": "test@gmail.com"
            }
        }
     *
     *
     * @response status=400 {
            "statusCode": 400,
            "message": "Required Field missing",
            "data": {
                "email": [
                    "The email field is required."
                ]
            }
        }
     *
     *
     *
     */


    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => ['required']
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Required Field missing',
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $newsSubscription = new NewsSubscription();

        $newsSubscription->email=$request->email;
        $newsSubscription->save();

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Successfully news subscribed',
             'data'=>$newsSubscription->email,
        ])->setStatusCode(Response::HTTP_OK);
    }
}
