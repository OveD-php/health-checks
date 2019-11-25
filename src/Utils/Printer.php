<?php

namespace PhpSafari\Utils;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Printer
{

    public static function toString($var): String
    {
        if ($var === null) {
            return 'null';
        }

        if (is_string($var) || is_int($var)) {
            return (string)$var;
        }

        if (is_bool($var)) {
            return $var ? 'true' : 'false';
        }

        if (is_array($var)) {
            return self::arrayToString($var);
        }

        if (is_object($var) && method_exists($var, '__toString')) {
            return $var->__toString();
        }

        throw new Exception('Cannot convert input to string');
    }

    /**
     * @param $var
     * @return string
     */
    private static function arrayToString($var): string
    {
        $var = Arr::dot($var);
        $output = '[';
        foreach ($var as $key => $value) {
            $output .= sprintf('%s=%s, ', $key, $value);
        }
        $output = Str::replaceLast(', ', '', $output);
        $output .= ']';

        return $output;
    }
}
