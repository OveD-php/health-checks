<?php

namespace Vistik;

use Vistik\Checks\Check;
use Vistik\Exceptions\FailedHealthCheckException;
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

    public function run(): bool
    {
        if ($this->list->count() == 0) {
            return false;
        }

        /** @var Check $check */
        foreach ($this->list as $check) {
            if (!$check->run()) {
                throw new FailedHealthCheckException($check, "Failed health check: " . get_class($check));
            }
        }

        return true;
    }
}
