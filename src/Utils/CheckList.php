<?php

namespace Vistik\Utils;

use Vistik\Checks\HealthCheck;
use Vistik\Collections\TypedCollection;

class CheckList extends TypedCollection
{
    protected $type = HealthCheck::class;
}
