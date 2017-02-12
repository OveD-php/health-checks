<?php

use Orchestra\Testbench\TestCase;
use Vistik\Checks\DebugModeCheck;
use Vistik\HealthCheckServiceProvider;

class CommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [HealthCheckServiceProvider::class];
    }

    /**
     * @test
     * @group cli
     * @expectedException Vistik\Exceptions\FailedHealthCheckException
     * @expectedExceptionMessage Failed health check: Vistik\Checks\DebugModeCheck
     */
    public function can_run_health_check_from_command_line()
    {
        // Given
        $this->app['config']->set('app.debug', true);
        $this->app['config']->set('health.checks', [new DebugModeCheck()]);

        // When
        $this->artisan('health:check');

        // Then

    }
}