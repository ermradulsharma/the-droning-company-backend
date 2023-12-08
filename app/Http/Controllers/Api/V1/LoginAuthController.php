<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class LoginAuthController extends Controller
{

    /**
     * Login Api.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `401` error, and a response invalid credentials
     *
     *
     * @bodyParam email string required The email of the user. Example: example@domain.com
     * @bodyParam password string required The password of the user. Example: secret
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
            "statusCode": 200,
            "message": "User data fetch successfully",
            "data": {
                "id": 1,
                "first_name": "Admin",
                "last_name": "admin",
                "email": "admin@admin.com",
                "slug": null,
                "roles": [
                    {
                        "id": 1,
                        "title": "Admin"
                    }
                ]
            },
            "access_token": "42|WBh8auQX8LItsHokZAHplNmnu5zYFGsVEEXWaBpj"
        }
      *
      * @response status=401 {
            "statusCode": 401,
            "message": "Invalid Login credentials or data does not exists in our records",
            "data": []
        }

    */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                    'email' => 'required',
                    'password' => 'required',
                ]);
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'active_status'=>'1'])) {
                // Authentication passed...
                if (auth()->user()) {
                    $user = User::with(['roles:id,title'])
                                ->where('email', $request->email)
                                ->first();


                    $subscriptions=  \Laravel\Cashier\Subscription::query()
                                        ->where('user_id', $user->id)
                                        ->active()
                                        ->first();

                    $subscription_on_grace_period=$subscriptions ? $subscriptions->onGracePeriod() :false;

                    $user['subscriptions']=$subscriptions;

                  
                    $authToken = $user->createToken($user->email)->plainTextToken;

                    if($user->roles[0]->id == 4){
                        $user['plans']=Plan::STRIP_COMPANY_PLAN_WITH_PRE_DEFINED_KEY;
                    }else{
                        $user['plans']=Plan::STRIP_All_PLAN_WITH_PRE_DEFINED_KEY;
                    }
                    
                    return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'User data fetch successfully',
                         'data'=>$user,
                         'subscription_on_grace_period'=>$subscription_on_grace_period,
                         'access_token' => $authToken,
                 ])->setStatusCode(Response::HTTP_OK);
                } else {
                    return $this->respondWithError(252, 401);
                }
            } else {
                return response()->json([
                         'statusCode'=>401,
                         'message' => 'Invalid Login credentials or data does not exists in our records',
                         'data'=>[],
                 ])->setStatusCode(401);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            return $this->respondWithError(250, 401);
        }
    }
}
