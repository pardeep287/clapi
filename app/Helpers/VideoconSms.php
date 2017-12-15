<?php

namespace App\Helpers;

class VideoconSms implements SmsInterface{

    private $senderID,$userName,$password ;
    private $baseURL = 'https://bulksmsapi.videoconsolutions.com/';

    public function __construct()
    {
        $this->senderID = env('VIDEOCON_SMS_SENDER_ID');
        $this->userName = env('VIDEOCON_SMS_USERNAME');
        $this->password = env('VIDEOCON_SMS_PASSWORD');


    }
    public function send($recipient, $message, $messageType = 'text')
    {
        if ($this->senderID && $this->userName && $this->password) {
            $messageToSend = urlencode($message . " Thank you for choosing {$this->senderID}.");
            $params = "?username={$this->userName}"
                . "&"
                . "password={$this->password}"
                . "&"
                . "messageType={$messageType}"
                . "&"
                . "mobile={$recipient}"
                . "&"
                . "senderId={$this->senderID}"
                . "&"
                . "message={$messageToSend}";

            $requestURL = $this->baseURL . $params;


            $sslInfo = [
                "ssl" => [
                    "verify_peer"      => false,
                    "verify_peer_name" => false,
                ],
            ];
            $cxContext = stream_context_create($sslInfo);

            $resp['request'] = $requestURL;

            $resp['response'] = file_get_contents($requestURL, false, $cxContext);

            return $resp;

        } else {
            return [
                'response' => "INCORRECT Credentials",
            ];
        }
    }




}

