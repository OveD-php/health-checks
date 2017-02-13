<?php

use Vistik\Checks\DatabaseOnlineHealthCheck;
use Vistik\Checks\DebugModeOffHealthCheck;
use Vistik\Checks\CorrectEnvironmentHealthCheck;
use Vistik\Checks\QueueHealthCheck;

return [
    'checks' => [
        new DatabaseOnlineHealthCheck(),
        new DebugModeOffHealthCheck(),
        new CorrectEnvironmentHealthCheck('production'),
        new QueueHealthCheck()
    ]
];
