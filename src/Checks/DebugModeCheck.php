<?php

namespace Vistik\Checks;

use Illuminate\Support\Facades\Config;

class DebugModeCheck extends Check
{

    public function run(): bool
    {
        $debugMode = Config::get('app.debug');
        if (true === $debugMode) {
            $this->setError(sprintf('Debug mode was %s should have been false', $debugMode));

            return false;
        }

        return true;
    }
}