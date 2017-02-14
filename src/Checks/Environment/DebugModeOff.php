<?php

namespace Vistik\Checks\Environment;

use Vistik\Checks\HealthCheck;

class DebugModeOff extends HealthCheck
{

    public function run(): bool
    {
        $debugMode = config('app.debug');
        if (true === $debugMode) {
            $this->setError(sprintf('Debug mode was %s should have been false', $debugMode));

            return false;
        }

        return true;
    }
}
