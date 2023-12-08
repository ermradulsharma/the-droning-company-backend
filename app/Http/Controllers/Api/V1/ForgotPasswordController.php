<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\ForgotPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgotMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Forgot Password
     *
     * If everything is okay, you'll get a `200` OK response.
     *
     * Otherwise, the request will fail with a `404` error, and a response Email not found!
     *
     *
     * <aside class="notice">basepath/api/v1/forgot-password.</aside>
     * @queryParam ?email Example ?email=you@domain.com code Example test@gmail.com.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Successfully mail sent! Please check your mail inbox..",
            "token": "Fs5Orq3cxYT9RZi0MLYxVKlHqXmL098GMbB671MDyoPQdQOuvXjKBvqJMpsOB3AN"
        }
     *
     *
     *
     *  {
            "statusCode": 404,
            "message": "Email not found!",
            "data": []
        }
     *
     *
     *
     */

    public function index(Request $request)
    {
        $email = $request->email;

        $validation = Validator::make($request->all(), [
            'email'    => ['required'],
        ], [
            'email.required'=>'Email is required',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $getUser = User::query()
                        ->where('email', $email)
                         ->select('id', 'first_name', 'last_name', 'email')
                         ->first();
        if (!$getUser) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'Email not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        $user_id = $getUser->id;
        $token = Str::random(64);

        $data['user_id']=$user_id;
        $data['token']=$token;

        $forgot=ForgotPassword::create($data);

        Mail::to($email)->send(new SendForgotMail($getUser, $token));

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Successfully mail sent! Please check your mail inbox..',
             'token' => $token,
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Update Password
     *
     * If everything is okay, you'll get a `200` OK response Successfully Passsword reset.
     *
     * Otherwise, the request will fail with a `404` error, and a response You Are Not Autheticated To Forgot Password! or `400` New Password is required
     *
     *
     * <aside class="notice">basepath/api/v1/forgot-password.</aside>
     * @queryParam ?new_password Example ?new_password=secret Example Password@123.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     *
     * @response
     *  {
            "statusCode": 200,
            "message": "Successfully Passsword reset"
        }
     *
     *
     *
     *  {
            "statusCode": 404,
            "message": "You Are Not Autheticated To Forgot Password!",
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

    public function update(Request $request, $token)
    {
        // dd($request->all(), $token);
        // $token = base64_decode($request->token);
        // $new_pass = bcrypt($request->new_password);

        // $validation = Validator::make($request->all(), [
        //     'new_password'    => ['required'],
        // ], [
        //     'new_password.required'=>'New Password is required',
        // ]);
       
        // if ($validation->fails()) {
        //     return response()->json([
        //         'statusCode' => Response::HTTP_BAD_REQUEST,
        //         'message' => $validation->messages()->first(),
        //         'data' => $validation->messages(),
        //     ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        // }

        // $getToken = ForgotPassword::query()
        //                 ->where('token', $token)
        //                 ->where('status', '1')
        //                  ->select('id', 'user_id')
        //                  ->orderBy('id', 'DESC')
        //                  ->first();

        // if (!$getToken) {
        //     return response()->json([
        //         'statusCode' =>404,
        //         'message' => 'You Are Not Autheticated To Forgot Password!',
        //         'data' =>[]
        //     ])->setStatusCode(404);
        // }

        // $user_id = $getToken->user_id;
        // $update_pass = User::where('id', $user_id)->limit(1)->update(['password' => $new_pass]);

        // if ($update_pass) {
        //     $timeStamp = date('Y-m-d H:i:s');
        //     $update_forgot = ForgotPassword::where('user_id', $user_id)->update(['deleted_at' => $timeStamp,'status'=>'0']);

        //     return response()->json([
        //          'statusCode'=>Response::HTTP_OK,
        //          'message' => 'Successfully Passsword reset!'
        //     ])->setStatusCode(Response::HTTP_OK);
        // }
        

      
        $validation = Validator::make($request->all(), [
            'new_password'    => ['required'],
        ], [
            'new_password.required'=>'New Password is required',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $getToken = Password::latest()->where('token', $token)->first();

    
        if (!$getToken) {
            return response()->json([
                'statusCode' =>400,
                'message' => 'You Are Not authenticated To Forgot Password!',
                'data' =>[]
            ])->setStatusCode(400);
        }

        $user= User::where('email', $getToken->email)->first();
        $user->password=bcrypt($request->new_password);
        $user->save();

        Password::latest()->where('token', $token)->delete();
     

        return response()->json([
                 'statusCode'=>Response::HTTP_OK,
                 'message' => 'Successfully Password changed!'
            ])->setStatusCode(Response::HTTP_OK);
    }


    public function reset(Request $request)
    {
        $email = $request->email;

        $validation = Validator::make($request->all(), [
            'email'    => ['required'],
        ], [
            'email.required'=>'Email is required',
        ]);
       
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $getUser = User::where('email', $request->email)
                         ->first();

        if (!$getUser) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' =>'Invalid Email address',
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $token = Str::random(64).time();
        $password=Password::create([
            'email'=>$request->email,
             'token'=> $token,
             'created_at'=>now()
 
        ]);
       
        Mail::to($request->email)->send(new SendForgotMail($getUser, $token));

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'Successfully mail sent! Please check your mail inbox..',
        ])->setStatusCode(Response::HTTP_OK);
    }
}
