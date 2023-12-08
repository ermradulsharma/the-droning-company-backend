<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    
    /**
     * Update Password
     *
     * If everything is okay, you'll get a `200` OK response Successfully Password reset.
     *
     * Otherwise, the request will fail with a `404` error, and a response You Are Not Autheticated To Forgot Password! or `400` New Password is required
     *
     *
     * <aside class="notice">basepath/api/v1/forgot-password.</aside>
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Password change successfully"
            data :[]
        }
     *
     *
     *
     *  {
            "statusCode": 400,
            "message": "old password and new password did not match",
            "data": []
        }

        {
            "statusCode": 400,
            "message": "New Password is required",
            "data": {
                "new_password": [
                    "New Password is required"
                ]
            }
        }
     *
     *
     *
     */

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // new password required
            'new_password'    => ['required'],
                // old password required
            'old_password'    => ['required'],
            // user id from login
            'user_id' => ['required','exists:users,id','numeric'],
        ], [
            'new_password.required'=>'New Password is required',
            'old_password.required'=>'New Password is required',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

       
        $user= User::where('id', $request->user_id)->first();
       
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'statusCode' =>400,
                'message' => 'old password is not correct',
                'data' =>[]
            ])->setStatusCode(400);
        }

        $user->password=bcrypt($request->new_password);
        $user->save();

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'Password changed successfully!',
                 'data' =>[]
            ])->setStatusCode(Response::HTTP_OK);
    }
}
