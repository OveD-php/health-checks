<?php

namespace Vistik\Utils;

use Vistik\Checks\Check;
use Vistik\Collections\TypedCollection;

class CheckList extends TypedCollection
{
    protected $type = Check::class;
}
