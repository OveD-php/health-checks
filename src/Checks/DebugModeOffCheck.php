<?php

namespace Vistik\Checks;

class DebugModeOffHealthCheck extends HealthCheck
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
