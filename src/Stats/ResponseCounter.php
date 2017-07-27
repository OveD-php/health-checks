<?php

namespace Vistik\Stats;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ResponseCounter
{

    private static $statusCodes = [200, 201, 204, 400, 401, 403, 404, 405, 422, 500];
    private static $successCodes = [200, 201, 204];
    private static $rememberInMinutes = 60;

    public static function addResponse(Response $response)
    {
        $key = self::getCacheKey($response->getStatusCode());
        if (Cache::has($key)) {
            Cache::increment($key);

            return;
        }

        Cache::put($key, 1, self::$rememberInMinutes);
    }

    public static function getStats()
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
        $output['success'] = [
            'count' => $successfulCount,
            'ratio' => ($successfulCount / $total) * 100,
        ];

        $output['total'] = $total;

        return $output;
    }

    public static function getCount(int $statusCode): int
    {
        $key = self::getCacheKey($statusCode);
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
        $key = self::getCacheKey($statusCode);
        $count = (int)Cache::get($key, 0);
        $total = (int)self::getTotalCount();

        if ($total == 0) {
            return 0;
        }

        return ($count / $total) * 100;
    }

    private static function getCacheKey(int $statusCode): string
    {
        $key = 'requests.' . $statusCode;

        return $key;
    }
}