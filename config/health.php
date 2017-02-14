<?php

use Vistik\Checks\Database\DatabaseOnline;
use Vistik\Checks\Environment\CorrectEnvironment;
use Vistik\Checks\Environment\DebugModeOff;
use Vistik\Checks\Filesystem\PathIsWritable;
use Vistik\Checks\Queue\QueueIsProcessing;

return [
    'checks' => [
        new DatabaseOnline(),
        new DebugModeOff(),
        new CorrectEnvironment('production'),
        new QueueIsProcessing(),
        new PathIsWritable(storage_path()),
        new PathIsWritable(storage_path('logs')),
        new PathIsWritable(storage_path('framework/session')),
        new PathIsWritable(storage_path('framework/cache')),
    ],
    'route'  => [
        'enabled' => true,
    ]
];
