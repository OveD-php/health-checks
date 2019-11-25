<?php

namespace PhpSafari\Utils;

use PhpSafari\Checks\HealthCheck;
use PhpSafari\Collections\TypedCollection;

class CheckList extends TypedCollection
{
    protected $type = HealthCheck::class;
}
