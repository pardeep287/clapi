<?php

namespace App\Events;

use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $verificationCode;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $user->jb_coins = $user->jb_coins + config('settings.earnings.on_referred');
        $user->save();
        
        $referrer = $user->referrer;
        if($referrer){
            $referrer->jb_coins = $referrer->jb_coins + config('settings.earnings.on_referring');
            $referrer->total_referrals = $referrer->total_referrals + 1;
            $referrer->save();
        }
    
        $this->user = $user;
        $this->verificationCode = strtoupper(substr(md5(str_random(20)), 0, 6));
        $this->user->verificationCodes()->save(new UserVerification([
            "code" => $this->verificationCode,
            "verification_type" => "activation"
        ]));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel([]);
    }
}
