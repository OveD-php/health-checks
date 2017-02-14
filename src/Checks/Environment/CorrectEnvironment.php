<?php

namespace Vistik\Checks\Environment;

use Vistik\Checks\HealthCheck;

class CorrectEnvironment extends HealthCheck
{
    private $env;

    public function __construct($env = 'production')
    {
        $this->env = $env;
    }

    public function run(): bool
    {
        $env = config('app.env');
        if ($env === $this->env) {
            return true;
        }
        $this->setError(sprintf('env is %s should be %s', $env, $this->env));

        return false;
    }
}
