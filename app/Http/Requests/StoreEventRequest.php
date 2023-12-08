<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        //return Gate::allows('pilot_access');
    }

    public function rules()
    {
        return [
            'title'        => [
                'string',
                'required',
            ],
            'image'        => [
                'file',
                'nullable',
            ],
            'description'=>['required','string'],
        ];
    }
}
