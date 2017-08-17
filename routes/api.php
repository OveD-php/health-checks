<?php

use Illuminate\Support\Facades\Route;
use PhpSafari\HealthChecker;
use PhpSafari\Metrics\Metrics;
use PhpSafari\Utils\CheckList;

Route::get(
    '_health',
    function () {
        if (!config('health.route.enabled', false)){
            return response('Route not found', 404);
        }

        $checks = config('health.checks');
        $checker = new HealthChecker(new CheckList($checks));

        if (!$checker->getOutcome()) {
            return response()->json(['health' => 'failed'], 500);
        }

        return response()->json(['health' => 'ok'], 200);
    }
);

Route::get(
    '_health/stats',
    function () {
        if (!config('health.route.enabled', false)){
            return response('Route not found', 404);
        }

        return response()->json(Metrics::getStats(), 200);
    }
);
