<?php

namespace PhpSafari\Checks;

abstract class HealthCheck
{
    private $error = '';
    private $log = [];

    abstract public function run(): bool;

    public function log(string $message)
    {
        $this->log[] = $message;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error)
    {
        $this->error = $error;
    }

    public function getLog()
    {
        return $this->log;
    }
}
