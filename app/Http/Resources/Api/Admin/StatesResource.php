<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\Resource;

class StatesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $res = [
            "id" => $this->id ,
            "name" => $this->name,

        ];


        if($request->noCountry === null) {
            $res['country_id'] = $this->country_id;
        }

        return $res;
    }
}
