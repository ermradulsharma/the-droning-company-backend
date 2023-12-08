<?php

namespace App\Http\Requests;

use App\Models\BlogCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('blog_category_create');
    }

    public function rules()
    {
        return [
            'title'        => [
                'string',
                'max:100',
                'required',
               // 'unique:blog_categories',
            ],
            'slug'         => [
                'string',
                'max:150',
                'nullable',
            ],
            'meta_keyword' => [
                'string',
                'nullable',
            ],
            'meta_title'   => [
                'string',
                'max:80',
                'nullable',
            ],
        ];
    }
}
