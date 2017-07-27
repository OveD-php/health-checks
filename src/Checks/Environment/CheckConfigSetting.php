<?php

namespace Vistik\Checks\Environment;


use Vistik\Checks\HealthCheck;
use Vistik\Utils\Printer;

class CheckConfigSetting extends HealthCheck
{

    /**
     * @var
     */
    protected $setting;
    /**
     * @var
     */
    protected $value;

    public function __construct(string $setting, $value)
    {
        $this->setting = $setting;
        $this->value = $value;
    }

    public function run(): bool
    {
        $this->log(sprintf('Checking if config setting <comment>%s</comment> has correct value: <comment>%s</comment>', $this->setting, Printer::toString($this->value)));
        $value = config($this->setting, null);
        if ($value === null) {
            $this->setError(sprintf('Config setting <comment>%s</comment> is not set!', $this->setting));

            return false;
        }

        if ($value != $this->value) {
            $this->setError(sprintf("Expected value <comment>%s</comment> does not match actual value: <comment>%s</comment>", Printer::toString($this->value), Printer::toString($value)));

            return false;
        }

        return true;
    }
}