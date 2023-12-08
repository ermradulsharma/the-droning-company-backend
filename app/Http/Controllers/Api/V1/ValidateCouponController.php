<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Plan;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidateCouponController extends Controller
{

    /**
     * Verify Coupon Code.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `401` or `400` error, and a response
        (`required field validation` or `Invalid coupon code`)
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
             "statusCode": 200,
             "message": "coupon apply successfully",
             "data": {
                "discount_amount": 4.9,
            }
        }
      *
      * @response status=401 {
            "statusCode": 401,
            "message": "Invalid coupon code",
            "data": []
        }

     * @response status=400 {
             "statusCode": 400,
             "message": "Required Field missing",
              "data": {
                "coupon_code": [
                    "The coupon code field is required."
                ]
            }
     }

    */
    public function verify(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'coupon_code' => ['required','string'],
            'coupon_plan_id'=> ['required','string']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' =>$validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if (!array_key_exists($request->coupon_plan_id, Plan::STRIP_DETAIL_FROM_STRIP)) {
            return response()->json([
                         'statusCode'=>401,
                         'message' => 'Invalid Coupon plan id',
                         'data'=>[]
                     ])->setStatusCode(401);
        }
        $plan_amount=Plan::PLAN_AMOUNT_FROM_STRIPE[$request->coupon_plan_id];

        if ($request->coupon_code=="fixed1dollar") {
            return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'coupon validate successfully',
                         'data'=>[
                            'discount_amount'=>'1',
                            'final_pay'=>$plan_amount-1,
                         ],
                     ])->setStatusCode(Response::HTTP_OK);
        }
        $coupon=Coupon::where('coupon_code', $request->coupon_code)
                            ->latest()
                            ->first();


        if (!$coupon) {
            return response()->json([
                'statusCode' =>401,
                'message' => 'Invalid coupon code',
                'data' =>[]
            ])->setStatusCode(401);
        }



        $coupon_info_arr=[];

        if ((int)$coupon->coupon_type===1) {
            $discount_amount=($coupon->discount/100)*$plan_amount;
            $coupon_info_arr['discount_amount']=$discount_amount;
            $final_pay=$plan_amount-$discount_amount;
        } else {
            $coupon_info_arr['discount_amount']=(int)$coupon->discount;
            $final_pay=$plan_amount-$coupon->discount;
        }

        $coupon_info_arr['final_pay']=$final_pay > 0 ? $final_pay : 0;

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'coupon apply successfully',
                         'data'=>$coupon_info_arr,
                     ])->setStatusCode(Response::HTTP_OK);
    }
}
