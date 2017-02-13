<?php

namespace Vistik\Exceptions;

use Exception;
use Vistik\Checks\HealthCheck;

class FailedHealthCheckException extends Exception
{

    /**
     * @var HealthCheck
     */
    private $check;

    public function __construct(HealthCheck $check, $message = "", $code = 0, Exception $previous = null)
    {
        $this->check = $check;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return HealthCheck
     */
    public function getCheck(): HealthCheck
    {
        return $this->check;
    }
}
