<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Helpers\VideoconSms;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationCodeMobile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {

        if(strlen($event->user->phone_number) > 0) {
            $message='Your OTP to login at clubjb.com is ' . $event->verificationCode;
            (new VideoconSms)->send($event->user->phone_number, $message, $messageType = 'text');
            //Log::info("Sending verification code " . $event->verificationCode . " to " . $event->user->phone_number);
        }
    }
}
