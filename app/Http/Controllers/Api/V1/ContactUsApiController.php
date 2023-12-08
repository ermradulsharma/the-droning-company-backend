<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ContactUsApiController extends Controller
{
    /**
     * Contact-us Api.
     * If everything is okay,you'll get a `200` OK response.
     *
     * Otherwise, the request will fail with a `403` error, Required Field missing!
     *
     *
     * <aside class="notice">basepath/api/v1/contact-us.</aside>
     *
     *
     * @queryParam ?name Required Example ?name= some name Example John.
     * @queryParam ?email Required Example ?email=emailId Example john@domain.com
     * @queryParam ?mesage Example ?message=some text Example Hi this is John wanted to know something....
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @response
     * {
            "statusCode": 200,
            "message": "Successfully news subscribed",
            "data": {
                "name": "Test",
                "email": "test@gmail.com",
                "message": "this is test message",
                "updated_at": "2021-07-26 09:53:10",
                "created_at": "2021-07-26 09:53:10",
                "id": 1
            }
        }
     *
     *
     *
     * @response status=400 {
            "statusCode": 400,
            "message": "Required Field missing",
            "data": {
                "name": [
                    "The name field is required."
                ],
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
            'name' => ['required'],
            'email' => ['required']
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Required Field missing',
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $ContactUs = new ContactUs();

        $ContactUs->name=$request->name;
        $ContactUs->email=$request->email;
        $ContactUs->message=$request->message;
        $ContactUs->save();

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Successfully news subscribed',
             'data'=>$ContactUs,
        ])->setStatusCode(Response::HTTP_OK);

        
        return response()->json([
         'statusCode'=>Response::HTTP_OK,
         'message' => 'Pilot Search result fetch successfully',
         'data'=>$pilotResults
        ]);
    }
}
