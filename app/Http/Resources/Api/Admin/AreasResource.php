<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\Resource;

class AreasResource extends Resource
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


        if($request->noCity === null) {
            $res['city_id'] = $this->city_id;
        }

        return $res;
    }
}
