<?php

namespace Phpsafari\Checks\Application;

use Phpsafari\Checks\HealthCheck;
use Phpsafari\Metrics\Metrics;
use Phpsafari\Utils\Printer;

class MaxResponseTimeAvg extends HealthCheck
{

    /**
     * @var float
     */
    private $max;

    public function __construct(float $max)
    {
        $this->max = $max;
    }

    public function run(): bool
    {
        $ratio = Metrics::getResponseTimeAvg();
        $success = $ratio < $this->max;
        $this->log("Checking " . $ratio. "ms(actual) < " . $this->max . 'ms(max ratio) = ' . Printer::toString($success));

        return $success;
    }
}
