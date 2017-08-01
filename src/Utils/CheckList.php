<?php

namespace Phpsafari\Utils;

use Phpsafari\Checks\HealthCheck;
use Vistik\Collections\TypedCollection;

class CheckList extends TypedCollection
{
    protected $type = HealthCheck::class;
}
