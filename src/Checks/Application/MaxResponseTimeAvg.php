<?php

namespace Vistik\Checks\Application;

use Vistik\Checks\HealthCheck;
use Vistik\Metrics\Metrics;
use Vistik\Utils\Printer;

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
        $this->log("Checking $ratio%(actual) < " . $this->max . '%(max ratio) = ' . Printer::toString($success));

        return $success;
    }
}