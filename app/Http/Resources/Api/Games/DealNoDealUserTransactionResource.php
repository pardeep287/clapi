<?php

namespace App\Http\Resources\Api\Games;

use Illuminate\Http\Resources\Json\Resource;

class DealNoDealUserTransactionResource extends Resource
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
            "description" => $this->description,
            "entrance_fee" => $this->entrance_fee,
            "amount" => $this->amount,
            "status" => $this->status,
        ];
    }
}
