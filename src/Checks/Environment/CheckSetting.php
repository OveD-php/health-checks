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
        $this->log(sprintf('Checking if setting <comment>%s</comment> has correct value: <comment>%s</comment>', $this->setting, $this->value));
        $value = config($this->setting, null);
        if ($value === null) {
            $this->setError(sprintf('Setting <comment>%s</comment> is not set!', $this->setting));

            return false;
        }

        if ($value != $this->value) {
            $this->setError(sprintf("Expected value <comment>%s</comment> does not match actual value: <comment>%s</comment>", $this->value, $value));

            return false;
        }

        return true;
    }
}