<?php

namespace Vistik\Exceptions;

use Exception;
use Vistik\Checks\Check;

class FailedHealthCheckException extends Exception
{

    /**
     * @var Check
     */
    private $check;

    public function __construct(Check $check, $message = "", $code = 0, Exception $previous = null)
    {
        $this->check = $check;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Check
     */
    public function getCheck(): Check
    {
        return $this->check;
    }
}
