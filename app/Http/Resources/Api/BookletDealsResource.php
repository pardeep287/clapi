<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Api\BookletMembershipsResource;

class BookletDealsResource extends Resource
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
            "store_name" => $this->store_name,
            "deal_name" => $this->deal_name,
            "actual_price" => $this->actual_price,
            "discount_price" => $this->discount_price,
            "payable_price" => $this->payable_price,
            "terms" => $this->terms,
            "image_url" => url('booklets/deals') . '/' . $this->image_path
        ];
    }
}
