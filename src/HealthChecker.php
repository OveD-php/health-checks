<?php

namespace Vistik;

use Exception;
use Illuminate\Support\Facades\Log;
use Vistik\Checks\Check;
use Vistik\Exceptions\FailedHealthCheckException;
use Vistik\Exceptions\NoHealthChecksSetupException;
use Vistik\Utils\CheckList;

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

        /** @var Check $check */
        foreach ($this->list as $check) {
            if (!$check->run()) {
                throw new FailedHealthCheckException($check, "Failed health check: " . get_class($check));
            }
        }
    }

    public function getOutcome(): bool
    {
        $outcome = false;
        try{
            $this->run();
            $outcome = true;
        } catch (Exception $e){
            Log::error($e);
        }

        return $outcome;
    }
}
