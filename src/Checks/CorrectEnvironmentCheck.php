<?php

namespace Vistik\Checks;

class CorrectEnvironmentCheck extends Check
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
