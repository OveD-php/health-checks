<?php

namespace Vistik\Checks\Application;

use Vistik\Checks\HealthCheck;
use Vistik\Metrics\Metrics;

class Response500Check extends HealthCheck
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
        $this->log("Checking $ratio%(actual) < " . $this->maxRatio . '%(max ratio) = ' . ($success ? 'true' : 'false'));

        return $success;
    }
}