<?php

namespace Vistik\Checks;

abstract class Check
{
    private $error;
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
        $this->log('[ERROR]:' . $error);
        $this->error = $error;
    }

    public function getLog()
    {
        return $this->log;
    }
}
