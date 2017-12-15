<?php 

namespace App\Exceptions;

class ResourceNotFoundException extends \Exception {
    private $statusCode = 40001;

    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        if($code === 0) {
            $code = $this->statusCode;
        }

        parent::__construct($message, $code, $previous);
    }
}