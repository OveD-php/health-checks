<?php

namespace Phpsafari\Checks\Application;

use Phpsafari\Checks\Environment\CheckConfigSetting;

class LogLevel extends CheckConfigSetting
{

    public function __construct($logLevel = 'debug')
    {
        parent::__construct('app.log_level', $logLevel);
    }
}
