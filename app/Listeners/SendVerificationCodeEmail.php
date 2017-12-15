<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\RegisterVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationCodeEmail implements ShouldQueue
{
    public $queue = 'emails';
    public $tries = 3;

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
        if(strlen($event->user->email_address) > 0) {
            Mail::to($event->user->email_address)
                ->send(new RegisterVerification($event->user, $event->verificationCode));
        }
    }
}
