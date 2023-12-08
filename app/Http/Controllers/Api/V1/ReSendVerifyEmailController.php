<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Mail\EmailVerify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReSendVerifyEmailController extends Controller
{
    /**
    * ReSend Verification Email
    * If everything is okay, you'll get a `200` OK response with data.
    *
    * Otherwise, the request will fail with a `404` error, and a response about data not found!
    *
    *
    * @return \Illuminate\Http\Response
    *
    * @response
    *  {
           "statusCode": 200,
           "message": "Verification Email Send Successfully"

       }
    *
    *
    * @response status=404 {
           "statusCode": 404,
           "message": "invalid email",
           "data": []
       }
    *
    *
    *
    */
    public function __invoke(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'    => ['required','exists:users,email'],
        ], [
            'email.required'=>'Email name is required',
            'email.users'=>'Email does not exists',
        ]);


        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $getUser = User::where('email', $request->email)->first();

        

        try {
            Mail::to($request->email)->send(new EmailVerify($getUser));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'Verification Email Send Successfully',
                 ])->setStatusCode(Response::HTTP_OK);
    }
}
