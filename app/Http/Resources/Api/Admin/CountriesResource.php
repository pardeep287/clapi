<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\Resource;

class CountriesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "dial_code" => $this->dial_code,
            "country_code" => $this->country_code,
            "locale" => $this->locale,
        ];
    }
}
