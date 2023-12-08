<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'first_name'     => [
                'string',
                'required',
            ],
            'last_name'     => [
                'string',
                'required',
            ],
            'email'    => [
                'required',
                'unique:users',
            ],
            'password' => [
                'required',
            ],
            'roles.*'  => [
                'integer',
            ],
            'roles'    => [
                'required',
                'array',
            ],
            'country_id.*'  => [
                'integer',
            ],
            'country_id'    => [
                'required',
            ],
        ];
    }
}
