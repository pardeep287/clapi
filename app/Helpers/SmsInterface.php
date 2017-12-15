<?php
namespace App\Helpers;

interface SmsInterface
{
    public function send($recipient, $message, $messageType = 'text');

}