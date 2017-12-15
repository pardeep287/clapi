<?php 

namespace App\Exceptions;

class PersonalRuntimeException extends \Exception {
    private $errorCode = 40002;

    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        if($code === 0) {
            $code = $this->errorCode;
        }

        parent::__construct($message, $code, $previous);
    }
}