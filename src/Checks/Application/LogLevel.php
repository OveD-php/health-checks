<?php

namespace PhpSafari\Checks\Application;

use PhpSafari\Checks\Environment\CheckConfigSetting;

class LogLevel extends CheckConfigSetting
{

    public function __construct($logLevel = 'debug')
    {
        parent::__construct('app.log_level', $logLevel);
    }
}
