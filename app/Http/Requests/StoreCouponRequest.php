<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCouponRequest extends FormRequest
{
    public function rules()
    {
        return [
            'coupon_name'     => [
                'string',
                'required',
                'unique:coupons',
            ],
            'coupon_code'     => [
                'regex:/^[a-zA-Z0-9]+$/',
                'required',
                'unique:coupons',
            ],
            'coupon_type'  => [
                'integer',
                'required',
            ],
            'discount' => [
                'required',
            ],
          
        ];
    }


    public function messages()
    {
        return [
        'coupon_code.regex:/^[a-zA-Z0-9]+$/' => 'space and special character not allowed',
       
    ];
    }
}
