<?php 

namespace App\Exceptions;

class PersonalValidationException extends \Exception {
    private $errorCode = 40003;
    private $errors = null;

    public function __construct($message, $errors, $code = 0, \Exception $previous = null)
    {
        if($code === 0) {
            $code = $this->errorCode;
        }

        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    public function errors() {
        return $this->errors;
    }
}