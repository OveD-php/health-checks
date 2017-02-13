<?php

namespace Vistik\Checks;

use Exception;
use Illuminate\Support\Facades\DB;

class DatabaseOnlineCheck extends Check
{

    public function run(): bool
    {
        try {
            if (DB::connection()->getPdo()) {
                return true;
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }

        return false;
    }
}
