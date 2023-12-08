<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
    {
        return [
        'status'=>'success'
      ];
    }
}
