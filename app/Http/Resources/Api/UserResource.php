<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
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
            "jb_coins" => (int)$this->jb_coins,
            "name" => $this->name,
            "phone_number" => $this->phone_number,
            "email_address" => $this->email_address,
            "is_verified" => (int)$this->is_verified,
            "is_active" => (int)$this->is_active,
            "referral_code" => $this->referral_code,
            "total_referrals" => (int)$this->total_referrals,
            "country" => $this->country,
            "state" => $this->state,
            "city" => $this->city,
        ];
    }

    public function with($request) {
        return [
            "meta" => [
                "tokens" => $request->_tokens_
            ]
        ];
    }
}
