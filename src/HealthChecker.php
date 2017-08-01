<?php

namespace Phpsafari;

use Exception;
use Illuminate\Support\Facades\Log;
use Phpsafari\Checks\HealthCheck;
use Phpsafari\Exceptions\FailedHealthCheckException;
use Phpsafari\Exceptions\NoHealthChecksSetupException;
use Phpsafari\Utils\CheckList;

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
