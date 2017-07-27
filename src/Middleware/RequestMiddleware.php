<?php

namespace Vistik;

use Closure;
use Vistik\Stats\ResponseCounter;

class RequestMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        ResponseCounter::addResponse($response);
    }
}