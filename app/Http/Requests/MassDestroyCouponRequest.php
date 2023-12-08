<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCouponRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:coupons,id',
        ];
    }
}
