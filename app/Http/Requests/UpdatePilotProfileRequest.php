<?php

namespace App\Http\Requests;

use App\Models\PilotProfile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePilotProfileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pilot_profile_edit');
    }

    public function rules()
    {
        return [
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
