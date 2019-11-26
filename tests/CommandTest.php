<?php

use Orchestra\Testbench\TestCase;
use PhpSafari\Checks\Environment\DebugModeOff;
use PhpSafari\Exceptions\FailedHealthCheckException;
use PhpSafari\ServiceProvider\HealthCheckServiceProvider;

class CommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [HealthCheckServiceProvider::class];
    }

    /**
     * @test
     * @group cli
     */
    public function running_health_check_from_command_line_will_throw_exception()
    {
        // Given
        $this->app['config']->set('app.debug', true);
        $this->app['config']->set('health.checks', [new DebugModeOff()]);

        // Assert
        $this->expectException(FailedHealthCheckException::class);
        $this->expectExceptionMessage('Failed health checks: DebugModeOff');

        // When
        $this->artisan('health:check');

        // Then

    }

    /**
     * @test
     * @group cli
     */
    public function can_run_health_check_from_command_line()
    {
        // Given
        $this->app['config']->set('app.debug', false);
        $this->app['config']->set('health.checks', [new DebugModeOff()]);

        // When
        $this->artisan('health:check');

        // Then
        $this->assertTrue(true);

    }
}
