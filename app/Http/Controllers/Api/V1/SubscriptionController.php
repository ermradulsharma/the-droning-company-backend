<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Plan;
use App\Models\User;
use App\Models\Coupon;
use \DateTimeInterface;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\SubscriptionCancel;
use App\Mail\SubscriptionResume;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriptionPaymentHistory;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{

    /**
     *
     * User Registration Step2.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `400` error, and a response
        (`Invalid token` or `mismatch token`).
     *
     *
     * <aside class="warning">3rd party payment gateway involved here</aside>
     *
     *
     * @bodyParam user_id int required The id of the user. Example: 10
     * @bodyParam plan_id int required right know it's a fixed value `1`. Example: 1
     * @bodyParam payment_id string required
     * @bodyParam payment_refrence string required
     * @bodyParam plan_amount int required Example: 49
     * @bodyParam final_pay int required Example: 49
     * @bodyParam coupon_code string. Example: null
     * @bodyParam coupon_discount_amount int Example: 0
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response status=200  {
            "statusCode": 200,
            "message": "User 2nd step registration successfully completed",
            "data": {
                "user_id": 1,
                "plan_id": 1,
                "start_date": "2021-06-11 00:00:00",
                "end_date": "2021-07-11 00:00:00",
                "status": true,
                "is_trial_period": false,
                "updated_at": "2021-06-11 11:29:10",
                "created_at": "2021-06-11 11:29:10",
                "id": 7
            }
        }
     *
     * @response status=400 {
            "statusCode": 400,
            "message": "Invalid token",
            "data":[]
        }

     * @response status=400 {
            "statusCode": 400,
            "message": "mismatch token",
            "data":[]
        }

     * @authenticated
     */
    public function step2(Request $request)
    {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if (!$personalAccessToken) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid token',
                'data' => [],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = $personalAccessToken->tokenable;

        if ($user->id != $request->user_id) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'mismatch token',
                'data' => [],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $validation = Validator::make($request->all(), [
            'user_id' => ['required'],
            'payment_id' => ['required'],
            'payment_refrence' => ['required'],
            'plan_amount' => ['required'],
            'final_pay' => ['required']

        ]);

        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => 'Required Field missing',
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $subscription = new Subscription();

        $subscription->user_id = $request->user_id;
        $subscription->plan_id = 1;
        $subscription->start_date = now()->format('Y-m-d');
        $subscription->end_date = now()->addDays(30)->format('Y-m-d');
        $subscription->status = true;
        $subscription->is_trial_period = false;
        $subscription->save();

        $sub_payment = new SubscriptionPaymentHistory();
        $sub_payment->subscription_id = $subscription->id;
        $sub_payment->plan_id = 1;
        $sub_payment->payment_id = $request->payment_id;
        $sub_payment->payment_refrence = $request->payment_refrence;
        $sub_payment->plan_amount = $request->plan_amount;
        $sub_payment->payment_date = now();
        $sub_payment->status = true;
        $sub_payment->coupon_code = $request->coupon_code ?? null;
        $sub_payment->coupon_discount_amount = $request->coupon_discount_amount ?? 0;
        $sub_payment->final_pay = $request->final_pay;
        $sub_payment->save();

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'User 2nd step registration successfully completed',
            'data' => $subscription
        ])->setStatusCode(Response::HTTP_OK);
    }


    /**
     *
     * Subscription create
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *

     *
     *
     * <aside class="warning">3rd party payment gateway involved here</aside>
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     */
     /* @response status=200  {
            {
            "statusCode": 200,
            "message": "subscription created successfully completed",
            "data": {
                    "subscription_status": true,
                    "user": {
                        "id": 65,
                        "first_name": "Test65",
                        "last_name": "Last name",
                        "slug": "test65-last-name",
                        "email": "d65@gmail.com",
                        "email_verified_at": null,
                        "country_id": null,
                        "registration_source": "Frontend",
                        "active_status": "1",
                        "yes_send_email": 0,
                        "yes_i_agree": 1,
                        "created_at": "2021-08-29 04:55:40",
                        "updated_at": "2021-08-29 05:01:51",
                        "deleted_at": null,
                        "hear_about_us": "1",
                        "profile_photo": "http://local.drone/pilotNoImage.png",
                        "stripe_id": "cus_K7vvMviqbUv0Ye",
                        "pm_type": "visa",
                        "pm_last_four": "4242",
                        "trial_ends_at": null,
                        "subscriptions": [
                            {
                                "id": 19,
                                "user_id": 65,
                                "name": "daily_plan",
                                "stripe_id": "sub_K7w1awfOssh04N",
                                "stripe_status": "active",
                                "stripe_price": "price_1JTKEdBbrKa9p7qI48PLqu3p",
                                "quantity": 1,
                                "trial_ends_at": null,
                                "ends_at": null,
                                "created_at": "2021-08-29T05:01:54.000000Z",
                                "updated_at": "2021-08-29T05:01:54.000000Z",
                                "items": [
                                    {
                                        "id": 19,
                                        "subscription_id": 19,
                                        "stripe_id": "si_K7w1oblDPdN3fS",
                                        "stripe_product": "prod_K7ZNT5eWHncExw",
                                        "stripe_price": "price_1JTKEdBbrKa9p7qI48PLqu3p",
                                        "quantity": 1,
                                        "created_at": "2021-08-29T05:01:54.000000Z",
                                        "updated_at": "2021-08-29T05:01:54.000000Z"
                                    }
                                ]
                            }
                        ]
                    }
                }
            }
        }
     *
     * @response status=400 {
            "statusCode": 200,
            "message": "Subscription already activated on this plan- daily_plan",
            "data":[]
        }

     */

    public function stripeSubscriptionCreate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            //user id required - get from pilot response id
            'user_id' => ['required', 'exists:users,id', 'numeric'],
            //strip pm get when you call strip api
            'stripe_pm_id' => ['required'],
            //plan id required - get from pilot response within plans []
            'plan_id' => ['required'],
            // optional if any coupon code exists
            'coupon_code' => ['nullable'],

        ]);

        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->getMessages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }


        $user = User::where('id', $request->user_id)->first();
        $pay = $user->createSetupIntent();

        $plan_name = Plan::STRIP_DETAIL_FROM_STRIP[$request->plan_id];


        try {
            if ($user->subscribed($plan_name)) {
                return response()->json([
                    'statusCode' => Response::HTTP_OK,
                    'message' => 'Subscription already activated on this plan-' . $plan_name,
                    'data' => [],
                ])->setStatusCode(Response::HTTP_OK);
            }
            if ($request->has('coupon_code') && $request->input('coupon_code') != '') {
                $promo = Coupon::where('coupon_code', $request->input('coupon_code'))->first();
                $promo_id = $promo->stripe_promotion_id;
                $sub = $user->newSubscription($plan_name, $request->plan_id)
                    ->withPromotionCode($promo_id)
                    ->create($request->input('stripe_pm_id'));
            } else {
                $sub = $user->newSubscription($plan_name, $request->plan_id)
                    ->create($request->input('stripe_pm_id'));
            }

            $user = User::query()
                ->with(['roles:id,title'])
                ->where('id', $request->user_id)
                ->first();


            $subscriptions =  \Laravel\Cashier\Subscription::query()
                ->where('user_id', $user->id)
                ->active()
                ->first();

            $user['subscriptions'] = $subscriptions;

            $data = [
                'subscription_status' => $user->subscribed($plan_name),
                'user' => $user,
            ];
            $authToken = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'statusCode' => Response::HTTP_OK,
                'message' => 'subscription created successfully completed',
                'data' => $data,
                'access_token' => $authToken,
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }


    /**
     *
     * Subscription Invoice
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     */
     /* @response status=200  {
                "statusCode": 200,
                "message": "invoice fetch successfully",
                "data": [
                {
                "customer": "cus_K7vvMviqbUv0Ye",
                "customer_email": "d65@gmail.com",
                "customer_name": "Test65 Last name",
                "subscription_id": "sub_K7w1awfOssh04N",
                "subscription_status": null,
                "invoice_pdf": "https://pay.stripe.com/invoice/acct_1JQxWjBbrKa9p7qI/invst_K9QwXGrKnlfecCg5hI0bwuNPlA0TXlE/pdf",
                "hosted_invoice_url": "https://invoice.stripe.com/i/acct_1JQxWjBbrKa9p7qI/invst_K9QwXGrKnlfecCg5hI0bwuNPlA0TXlE",
                "plan_id": "price_1JTKEdBbrKa9p7qI48PLqu3p",
                "plan_name": "daily_plan",
                "subscription_start": "2021-09-01 05:01:52",
                "subscription_end": "2021-09-02 05:01:52",
                "stripe_invoice_number": "89EB8641-0005",
                "paid": true,
                "payment_status": "paid",
                "sub_total": "$2.00",
                "total": "$2.00"
                },
                {
                "customer": "cus_K7vvMviqbUv0Ye",
                "customer_email": "d65@gmail.com",
                "customer_name": "Test65 Last name",
                "subscription_id": "sub_K7w1awfOssh04N",
                "subscription_status": null,
                "invoice_pdf": "https://pay.stripe.com/invoice/acct_1JQxWjBbrKa9p7qI/invst_K93iP8fcvYgacuyKWPgIXd94Cv7XlnQ/pdf",
                "hosted_invoice_url": "https://invoice.stripe.com/i/acct_1JQxWjBbrKa9p7qI/invst_K93iP8fcvYgacuyKWPgIXd94Cv7XlnQ",
                "plan_id": "price_1JTKEdBbrKa9p7qI48PLqu3p",
                "plan_name": "daily_plan",
                "subscription_start": "2021-08-31 05:01:52",
                "subscription_end": "2021-09-01 05:01:52",
                "stripe_invoice_number": "89EB8641-0004",
                "paid": true,
                "payment_status": "paid",
                "sub_total": "$2.00",
                "total": "$2.00"
                },
                ]

        }
     */
     /* @response status=404 {
            "statusCode": 404,
            "message": "subscription not activated",
            "data":[]
        }

     */

    public function stripeInvoice(int $user_id)
    {
        $user = \App\Models\User::where('id', $user_id)->first();
        $subscription = $user->subscriptions->first();


        if (!$subscription) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'subscription not activated',
                'data' => [],
            ])->setStatusCode(404);
        }

        $invoices = $user->invoices();



        $sub = [
            'plan_name' => $subscription->name,
            'payment_type' => $user->pm_type,
            'payment_last_four' => $user->pm_last_four,
            'subscription_id' => @$invoices[0]->subscription,
            'subscription_status' => $subscription->stripe_status,
            'renewal_amount' => @$invoices[0]->subtotal(),
            'plan_validity' => 'From ' . Carbon::parse(@$invoices[0]->lines->data[0]->period->start)->format("F d, Y") . ' To ' .  Carbon::parse(@$invoices[0]->lines->data[0]->period->end)->format("F d, Y")

        ];

        $data = [];
        foreach ($invoices as $key => $value) {
            $data[] = [
                'stripe_invoice_id' => $value->id,
                'stripe_invoice_number' => $value->number,
                'customer' => $value->customer,
                'customer_email' => $value->customer_email,
                'customer_name' => $value->customer_name,
                'subscription_id' => $value->subscription,
                'subscription_status' => $subscription->stripe_status,
                'invoice_pdf' => $value->invoice_pdf,
                'hosted_invoice_url' => $value->hosted_invoice_url,
                'plan_id' => $value->lines->data[0]->plan->id,
                'plan_name' => $subscription->name,
                'subscription_start' => Carbon::parse(@$value->lines->data[0]->period->start)->format('F d, Y'),
                'subscription_end' => Carbon::parse(@$value->lines->data[0]->period->end)->format('F d, Y'),
                'paid' => $value->paid,
                'payment_status' => $value->status,
                'sub_total' => $value->subtotal(),
                'discount' => $value->discount(),
                'total' => $value->total(),
            ];
        }

        $master = [];
        $master['subscriptions'] = $sub;
        $master['invoices'] = $data;

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'invoice fetch successfully',
            'data' => $master,
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Pilot subscription cancellation
     *
     * This endpoint allows you to cancelled the active subscription.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response status=200  {
            "statusCode": 200,
            "message": "subscription cancelled",
            "data": []
       }
     *

     * @response status=404 {
           "statusCode": 404,
            "message": "pilot does not have a valid subscription",
            "data": []
       }

     * @authenticated
     */

    public function subscriptionCancelled(int $user_id)
    {
        $user = User::where('id', $user_id)->with(['roles:id,title'])->first();
        $subscriptions =  \Laravel\Cashier\Subscription::query()
            ->where('user_id', $user_id)
            ->active()
            ->first();

        if ($user->roles[0]->id == 4) {
            $msg = 'Company does not have a valid subscription';
        } else {
            $msg = 'Pilot does not have a valid subscription';
        }

        if (!$subscriptions) {
            return response()->json([
                'statusCode' => 404,
                'message' => $msg,
                'data' => [],
            ])->setStatusCode(404);
        }

        try {
            $stripeCanclled = $user->subscription($subscriptions->name)->cancel();

            $invoices = $user->invoices();
            //dd($stripeCanclled);

            Mail::to($user->email)
                ->send(new SubscriptionCancel($user, $invoices[0]->id));


            return response()->json([
                'statusCode' => Response::HTTP_OK,
                'message' => 'subscription cancelled',
                'data' => [],
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Pilot subscription Resume
     *
     * This endpoint allows you to cancelled the active subscription.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the validation request will fail with a `400` error, and a response based on failed attribute
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response status=200  {
                "statusCode": 200,
                "message": "Subscription Resumed",
                "data": {
                "subscription_status": true,
                "user": {
                "id": 57,
                "first_name": "Stripe",
                "last_name": "Lastname",
                "slug": "stripe-lastname",
                "email": "striptest@gmail.com",
                "email_verified_at": null,
                "country_id": null,
                "registration_source": "Frontend",
                "active_status": "1",
                "yes_send_email": 0,
                "yes_i_agree": 1,
                "created_at": "2021-08-28 07:02:18",
                "updated_at": "2021-08-28 10:20:33",
                "deleted_at": null,
                "hear_about_us": "1",
                "profile_photo": "http://local.project1/pilotNoImage.png",
                "stripe_id": "cus_K7akeaGuKPwuUV",
                "pm_type": "visa",
                "pm_last_four": "4242",
                "trial_ends_at": null,
                "mobile": null,
                "subscriptions": [
                {
                "id": 2,
                "user_id": 57,
                "name": "daily_plan",
                "stripe_id": "sub_K7e5jnAECeO08t",
                "stripe_status": "active",
                "stripe_price": "price_1JTKEdBbrKa9p7qI48PLqu3p",
                "quantity": 1,
                "trial_ends_at": null,
                "ends_at": null,
                "created_at": "2021-08-28T10:29:42.000000Z",
                "updated_at": "2021-10-09T16:16:45.000000Z",
                "items": [
                {
                "id": 2,
                "subscription_id": 2,
                "stripe_id": "si_K7e5zRUvXErDq0",
                "stripe_product": "prod_K7ZNT5eWHncExw",
                "stripe_price": "price_1JTKEdBbrKa9p7qI48PLqu3p",
                "quantity": 1,
                "created_at": "2021-08-28T10:29:42.000000Z",
                "updated_at": "2021-08-28T10:29:42.000000Z"
                }
                ],
                "owner": {
                "id": 57,
                "first_name": "Stripe",
                "last_name": "Lastname",
                "slug": "stripe-lastname",
                "email": "striptest@gmail.com",
                "email_verified_at": null,
                "country_id": null,
                "registration_source": "Frontend",
                "active_status": "1",
                "yes_send_email": 0,
                "yes_i_agree": 1,
                "created_at": "2021-08-28 07:02:18",
                "updated_at": "2021-08-28 10:20:33",
                "deleted_at": null,
                "hear_about_us": "1",
                "profile_photo": "http://local.project1/pilotNoImage.png",
                "stripe_id": "cus_K7akeaGuKPwuUV",
                "pm_type": "visa",
                "pm_last_four": "4242",
                "trial_ends_at": null,
                "mobile": null
                }
                }
                ]
                }
},
"access_token": "78|AGq7GTNu2vLvBnNWP5pQDB8sO2ymccHk7jOHGgoA"
       }
     *

     * @response status=404 {
           "statusCode": 404,
            "message": "pilot in-active subscription does not exists,please create new subscription",
            "data": []
       }

     * @authenticated
     */

    public function subscriptionResume(int $user_id)
    {
        $user = User::where('id', $user_id)->with(['roles:id,title'])->first();
        $subscriptions =  \Laravel\Cashier\Subscription::query()
            ->where('user_id', $user_id)
            ->first();

        if ($user->roles[0]->id == 4) {
            $msg = 'Company in-active subscription does not exists,please create new subscription';
        } else {
            $msg = 'pilot in-active subscription does not exists,please create new subscription';
        }

        if (!$subscriptions) {
            return response()->json([
                'statusCode' => 404,
                'message' => $msg,
                'data' => [],
            ])->setStatusCode(404);
        }

        try {
            $stripeResume = $user->subscription($subscriptions->name)->resume();

            $user['subscriptions'] = $subscriptions;

            // dd($subscriptions);
            $data = [
                'subscription_status' => $user->subscribed($subscriptions->name),
                'user' => $user,
            ];
            $authToken = $user->createToken($user->email)->plainTextToken;
            $invoices = $user->invoices();

            Mail::to($user->email)
                ->send(new SubscriptionResume($user, $invoices[0]->id));

            return response()->json([
                'statusCode' => Response::HTTP_OK,
                'message' => 'Subscription Resumed',
                'data' => $data,
                'access_token' => $authToken,
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }


    public function updatePaymentMethod(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id', 'numeric'],
            'stripe_pm_id' => ['required'],
        ]);
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user = User::where('id', $request->user_id)->first();
        $role = (int)$user->roles()->pluck('role_id')[0];
        $redirect_url = ($role == 4) ? '/company-area/dashboard' : (($role == 3) ? '/pilot-area/dashboard' : '/user/dashboard');
        $user->createSetupIntent();
        try {
            $user->addPaymentMethod($request->stripe_pm_id);
            $user->updateDefaultPaymentMethod($request->stripe_pm_id);
            return response()->json([
                'statusCode' => Response::HTTP_OK,
                'message' => 'Transaction successfully approved.',
                'data' => $redirect_url,
            ])->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return $redirect_url;
            return response()->json([
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }
}
