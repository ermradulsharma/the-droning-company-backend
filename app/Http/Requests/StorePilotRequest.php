<?php

namespace App\Http\Requests;

use App\Models\PilotProfile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePilotRequest extends FormRequest
{
//    public function authorize()
//    {
//        return Gate::allows('permission_create');
//    }

    public function rules()
    {
        return [
            'user_id.*'    => [
                'integer',
            ],
            'title.*'    => [
                'string',
                'required',
            ],
            'is_certified.*' => [
                'string',
                'required',
            ],
            'travel_option.*' => [
                'string',
                'required',
            ],
            'is_featured.*' => [
                'string',
                'required',
            ],
            'description.*' => [
                'string',
                'required',
            ],
            
        ];
    }
}
