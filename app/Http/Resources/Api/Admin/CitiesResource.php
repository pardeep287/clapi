<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\Resource;

class CitiesResource extends Resource
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


        if($request->noState === null) {
            $res['state_id'] = $this->state_id;
        }

        return $res;
    }
}
