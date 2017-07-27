<?php

namespace Vistik\Middleware;

use Closure;
use Vistik\Metrics\Metrics;

class HealthMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Metrics::addData($response);
    }
}