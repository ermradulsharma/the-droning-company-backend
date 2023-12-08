<?php

namespace App\Http\Requests;

use App\Models\PilotGallery;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePilotGalleryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image.*' => [
                'file',
                'required',
            ],
        ];
    }
}
