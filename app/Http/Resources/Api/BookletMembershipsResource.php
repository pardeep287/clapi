<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;

class BookletMembershipsResource extends Resource
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
            "id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "validity_days" => $this->validity,
            "image_url" => url('booklets') . '/' . $this->image_path
        ];
    }
}
