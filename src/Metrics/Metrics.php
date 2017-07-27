<?php

namespace Vistik\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Metrics
{

    private static $statusCodes = [200, 201, 204, 400, 401, 403, 404, 405, 422, 500];
    private static $successCodes = [200, 201, 204];
    private static $rememberInMinutes = 60;

    public static function addData(Response $response)
    {
        $key = self::getHttpCodeCacheKey($response->getStatusCode());
        if (Cache::has($key)) {
            Cache::increment($key);
        } else{
            Cache::put($key, 1, self::$rememberInMinutes);
        }
    }

    public static function getStats(): array
    {
        $output = [];
        $total = 0;
        foreach (self::$statusCodes as $statusCode) {
            $count = self::getCount($statusCode);
            $output[$statusCode] = [
                'count' => $count,
                'ratio' => self::getRatio($statusCode),
            ];
            $total += $count;
        }

        $successfulCount = 0;
        foreach (self::$successCodes as $statusCode) {
            $count = self::getCount($statusCode);
            $successfulCount += $count;
        }

        if ($total > 0){
            $output['success'] = [
                'count' => $successfulCount,
                'ratio' => ($successfulCount / $total) * 100,
            ];
        }

        $output['total'] = $total;

        return $output;
    }

    public static function getCount(int $statusCode): int
    {
        $key = self::getHttpCodeCacheKey($statusCode);
        $count = (int)Cache::get($key, 0);

        return $count;
    }

    public static function getTotalCount(): int
    {
        $total = 0;
        foreach (self::$statusCodes as $statusCode) {
            $count = self::getCount($statusCode);
            $total += $count;
        }

        return $total;
    }

    public static function getRatio(int $statusCode): float
    {
        $key = self::getHttpCodeCacheKey($statusCode);
        $count = (int)Cache::get($key, 0);
        $total = (int)self::getTotalCount();

        if ($total == 0) {
            return 0;
        }

        return ($count / $total) * 100;
    }

    private static function getHttpCodeCacheKey(int $statusCode): string
    {
        $key = 'requests.httpcode.' . $statusCode;

        return $key;
    }
}