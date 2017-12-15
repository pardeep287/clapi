<?php

namespace App\Http\Resources\Api\Games;

use Illuminate\Http\Resources\Json\Resource;

class DealNoDealUserStatResource extends Resource
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
            "game_coins" => $this->when(($this->game_coins !== null), $this->game_coins),
            "gold" => $this->when(($this->gold !== null), $this->gold),
            "lifes" => $this->when(($this->lifes !== null), $this->lifes),
            "purchased_lifes" => $this->when(($this->purchased_lifes !== null), $this->purchased_lifes),
        ];
    }
}
