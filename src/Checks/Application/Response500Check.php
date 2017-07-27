<?php

namespace Vistik\Checks\Application;

use Vistik\Checks\HealthCheck;
use Vistik\Stats\ResponseCounter;

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
        return ResponseCounter::getRatio(500) < $this->maxRatio;
    }
}