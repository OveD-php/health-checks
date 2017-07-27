<?php

namespace Vistik\Checks\Application;

use Vistik\Checks\Environment\CheckEnvironmentSetting;

class LogLevel extends CheckEnvironmentSetting
{

    public function __construct($logLevel = 'debug')
    {
        parent::__construct('APP_LOG_LEVEL', $logLevel);
    }
}