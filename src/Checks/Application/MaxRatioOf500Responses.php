<?php

namespace Vistik\Checks\Application;

use Vistik\Checks\HealthCheck;
use Vistik\Metrics\Metrics;
use Vistik\Utils\Printer;

class MaxRatioOf500Responses extends HealthCheck
{

    /**
     * @var float
     */
    private $maxRatio;

    public function __construct(float $maxRatio)
    {
        $this->maxRatio = $maxRatio;
    }

    public function run(): bool
    {
        $ratio = Metrics::getRatio(500);
        $success = $ratio < $this->maxRatio;
        $this->log("Checking $ratio%(actual) < " . $this->maxRatio . '%(max ratio) = ' . Printer::toString($success));

        return $success;
    }
}
