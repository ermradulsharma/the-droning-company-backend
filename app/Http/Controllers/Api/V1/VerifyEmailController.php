<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Collection;

class VerifyEmailController extends Controller
{
    /**
     * User Email Verification
     *
     * If everything is okay, you'll get a `200` OK response.
     *
     * Otherwise, the request will fail with a `404` error, and a response User not found! or `400` Already verified! with data
     *
     *
     * <aside class="notice">basepath/api/v1/verify-email/{email}.</aside>
     *
     *
     * @return \Illuminate\Http\Response
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Successfully email verified"
        }
     *
     * {
            "statusCode": 400,
            "message": "Already verified!",
            "data": {
                "id": 18,
                "first_name": "Pqr",
                "last_name": "Abc",
                "email": "email@domain.com",
                "email_verified_at": "2021-08-08 14:13:06"
            }
        }
     *
     *  {
            "statusCode": 404,
            "message": "User not found!",
            "data": []
        }
     *
     *
     *
     */

    public function index(Request $request, $token)
    {
        $email = base64_decode($token);

        $getUser = User::query()
                        ->where('email', $email)
                         ->select('id', 'first_name', 'last_name', 'email', 'email_verified_at')
                         ->first();

        if (!$getUser) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'User not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        if (!empty($getUser->email_verified_at)) {
            return response()->json([
                'statusCode' =>400,
                'message' => 'Already verified!',
                'data' =>$getUser
            ])->setStatusCode(400);
        }

        $timeStamp = date('Y-m-d H:i:s');
        $update = User::where('email', $email)->limit(1)->update(['email_verified_at' =>$timeStamp]);

        if ($update) {
            return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'Successfully email verified'
            ])->setStatusCode(Response::HTTP_OK);
        }
    }

    public function fixed($token)
    {
        $getUser = User::with(['roles:id,title'])
                        ->where('email', base64_decode($token))
                         ->first();

        
        if (!$getUser) {
            return response()->json([
                'statusCode' =>400,
                'message' => 'Unable to Verify your email,please try again',
                'data' =>[]
            ])->setStatusCode(400);
        }

        $authToken = $getUser->createToken($getUser->email)->plainTextToken;

        if (!empty($getUser->email_verified_at)) {
            return response()->json([
                'statusCode' =>200,
                'message' => 'Email is already verified!',
                'email_verified_at'=>$getUser->email_verified_at,
                'data'=>$getUser,
                'access_token' => $authToken,
            ])->setStatusCode(200);
        }

        $getUser->email_verified_at=now();
        $getUser->save();

       

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'Successfully email verified',
                  'data'=>$getUser,
                  'access_token' => $authToken,
            ])->setStatusCode(Response::HTTP_OK);
    }
}
