<?php

namespace Vistik\Utils;

use Illuminate\Support\Arr;

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
            //$var = implode(',', $var);

            $var = Arr::dot($var);
            $output = '[';
            foreach ($var as $key => $value) {
                $output .= sprintf('%s=%s, ', $key, $value);
            }
            $output = str_replace_last(', ', '', $output);
            $output .= ']';

            return $output;
        }

        if (is_object($var) && method_exists($var, '__toString')) {
            return $var->__toString();
        }
    }
}
