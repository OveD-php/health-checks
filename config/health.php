<?php

use Vistik\Checks\DatabaseOnlineCheck;
use Vistik\Checks\DebugModeOffCheck;
use Vistik\Checks\CorrectEnvironmentCheck;
use Vistik\Checks\QueueCheck;

return [
    'checks' => [
        new DatabaseOnlineCheck(),
        new DebugModeOffCheck(),
        new CorrectEnvironmentCheck('production'),
        new QueueCheck()
    ]
];
