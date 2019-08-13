<?php

namespace PhpSafari\Utils;

use PhpSafari\Checks\HealthCheck;
use Vistik\Collections\TypedCollection;

class CheckList extends TypedCollection
{
    public function __construct($checks)
    {
        $cast = function ($check) {
            if ($check instanceof HealthCheck) {
                return $check;
            }
            if (is_string($check)) {
                return new $check;
            }
            if (is_array($check)) {
                return new $check[0](...array_slice($check, 1));
            }
        };
        
        parent::__construct(array_map($cast, $checks));
    }

    protected $type = HealthCheck::class;
}
