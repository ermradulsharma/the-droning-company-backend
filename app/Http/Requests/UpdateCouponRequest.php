<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCouponRequest extends FormRequest
{

    public function rules()
    {
        return [
            'coupon_name'     => [
                'string',
                'required',
            ],
            'coupon_type'     => [
                'integer',
                'required',
            ],
            'start_date'    => [
                'required',
            ],
            'end_date' => [
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
