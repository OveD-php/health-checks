<?php

use Vistik\Checks\CanConnectToDatabaseCheck;
use Vistik\Checks\DebugModeCheck;
use Vistik\Checks\EnvironmentCheck;
use Vistik\Checks\QueueCheck;

return [
    'checks' => [
        new CanConnectToDatabaseCheck(),
        new DebugModeCheck(),
        new EnvironmentCheck('production'),
        new QueueCheck()
    ]
];
