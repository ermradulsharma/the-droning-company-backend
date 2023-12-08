<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Requests\MassDestroyCouponRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;

class CouponsController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'DESC')->get();
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(StoreCouponRequest $request)
    {
        $stripe=new \Stripe\StripeClient(config('app.stripe_secret'));


        try {
            if ($request->coupon_type=="2") {
                $creatingCoupon=$stripe->coupons->create([
                      'currency'=>'usd',
                      'amount_off' =>$request->discount*100,
                      'duration' => 'once',
                      'name'=>$request->coupon_name,
                    
                ]);
            } else {
                $creatingCoupon=$stripe->coupons->create([
                      'currency'=>'usd',
                      'percent_off' =>$request->discount,
                      'duration' => 'once',
                      'name'=>$request->coupon_name,
                    
                ]);
            }
           

            $promotionCode=$stripe->promotionCodes->create([
              'coupon' =>$creatingCoupon->id,
              'code'=>$request->coupon_code,
              'restrictions'=>[
                'first_time_transaction'=>true
              ]
            ]);

            $data=$request->all();
            $data['stripe_coupon_id']=$creatingCoupon->id;
            $data['stripe_promotion_id']=$promotionCode->id;
            $data['status']=1;
            $coupon = Coupon::create($data);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        
        return redirect()->action([CouponsController::class, 'index'])->with('success', 'Successfully Coupon Created');
    }

    public function edit(Request $req)
    {
        $coupon = Coupon::find($req->coupon);
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(UpdateCouponRequest $req, Coupon $coupon)
    {
        $update =  $req->all();
        $update['coupon_name'] = str_replace(' ', '', strtoupper($req->coupon_name));
        
        $updateCoupon = $coupon->update($update);
        
        if ($updateCoupon) {
            return redirect()->action([CouponsController::class, 'index'])->with('success', 'Successfully Coupon Updated');
        } else {
            return redirect()->back();
        }
    }

    public function show(Request $req)
    {
        $coupon = Coupon::find($req->coupon);

        return view('admin.coupon.show', compact('coupon'));
    }
    
    
    public function destroy(Coupon $coupon)
    {
        try {
            $stripe=new \Stripe\StripeClient(config('app.stripe_secret'));

            $stripe->coupons->delete($coupon->stripe_coupon_id, []);
            $coupon->delete();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
       
        return redirect()->action([CouponsController::class, 'index'])
                ->with('success', 'Successfully Coupon Deleted');
    }
    
    public function massDestroy(MassDestroyCouponRequest $request)
    {
        Coupon::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massActiveStatus(Request $request)
    {
        Coupon::whereIn('id', $request->ids)->update(['status' => '1']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massInActiveStatus(Request $request)
    {
        Coupon::whereIn('id', $request->ids)->update(['status' => '0']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
