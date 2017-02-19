<?php

namespace Vistik\Checks\Environment;


use Vistik\Checks\HealthCheck;

class CheckSetting extends HealthCheck
{

    /**
     * @var
     */
    protected $setting;
    /**
     * @var
     */
    protected $value;

    public function __construct($setting, $value)
    {
        $this->setting = $setting;
        $this->value = $value;
    }

    public function run(): bool
    {
        $this->log(sprintf('Checking if setting %s has correct value: %s', $this->setting, $this->value));
        $value = config($this->setting, null);
        if ($value === null) {
            $this->setError(sprintf('Setting %s is not set!', $this->setting));

            return false;
        }

        if ($value != $this->value) {
            $this->setError(sprintf("Expected value %s does not match actual value: %s", $this->value, $value));

            return false;
        }

        return true;
    }
}