<?php

namespace App\Http\Requests;

use App\Models\PilotGallery;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePilotVideoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'video.*' => [
                'required',
                'active_url',
            ],
        ];
    }
}
