<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;

class UserVerificationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "success" => true
        ];
    }
}
