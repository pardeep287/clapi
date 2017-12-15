<?php 

namespace App\Exceptions;

class InvalidVerificationCodeException extends \Exception {
    private $statusCode = 40009;

    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        if($code === 0) {
            $code = $this->statusCode;
        }

        parent::__construct($message, $code, $previous);
    }
}