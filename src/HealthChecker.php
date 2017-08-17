<?php

namespace PhpSafari;

use Exception;
use Illuminate\Support\Facades\Log;
use PhpSafari\Checks\HealthCheck;
use PhpSafari\Exceptions\FailedHealthCheckException;
use PhpSafari\Exceptions\NoHealthChecksSetupException;
use PhpSafari\Utils\CheckList;

class HealthChecker
{
    /**
     * @var CheckList
     */
    private $list;

    public function __construct(CheckList $list)
    {
        $this->list = $list;
    }

    public function run()
    {
        if ($this->list->count() == 0) {
            throw new NoHealthChecksSetupException("No health check is setup!");
        }

        /** @var HealthCheck $check */
        foreach ($this->list as $check) {
            if (!$check->run()) {
                throw new FailedHealthCheckException("Failed health check: " . get_class($check) . print_r($check->getLog(), true));
            }
        }
    }

    public function prettyPrint()
    {
        $output = [];
        /** @var HealthCheck $check */
        foreach ($this->list as $check) {
            $output[] = [
                'passed' => $check->run(),
                'check'  => class_basename($check),
                'log'    => implode("\n", $check->getLog()),
                'error'  => $check->getError()
            ];
        }

        return $output;
    }

    public function getOutcome(): bool
    {
        $outcome = false;
        try {
            $this->run();
            $outcome = true;
        } catch (Exception $e) {
            Log::error($e);
        }

        return $outcome;
    }
}
