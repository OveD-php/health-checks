<?php

namespace Vistik\Checks;

use Exception;
use Illuminate\Support\Facades\DB;

class CanConnectToDatabaseCheck extends Check
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

    public function getError(): string
    {
        return $this->error;
    }
}