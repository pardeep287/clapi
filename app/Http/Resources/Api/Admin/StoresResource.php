<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\Resource;

class StoresResource extends Resource
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
            "id" => $this->id,
            "country" => $this->country,
            "state" => $this->state,
            "city" => $this->city,
            "category" => $this->category,
        ];

        if(!$request->noParent) {
            $res['parent'] = new CategoriesResource($this->parent);
        }

        return $res;
    }
}
