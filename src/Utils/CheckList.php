<?php

namespace PhpSafari\Utils;

use PhpSafari\Checks\HealthCheck;
use Vistik\Collections\TypedCollection;

class CheckList extends TypedCollection
{
    protected $type = HealthCheck::class;
}
