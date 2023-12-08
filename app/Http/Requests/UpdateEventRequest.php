<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('blog_edit');
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
            'slug'         => [
                'string',
                'required',
            ],
            'meta_keyword' => [
                'string',
                'nullable',
            ],
            'excerpt'=>['required','string'],
            'description'=>['required','string'],
        ];
    }
}
