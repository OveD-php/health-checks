<?php


use Orchestra\Testbench\TestCase;
use Vistik\Checks\DebugModeOffHealthCheck;
use Vistik\Checks\QueueHealthCheck;
use Vistik\HealthCheckServiceProvider;

class HealthCheckRouteTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [HealthCheckServiceProvider::class];
    }

    /**
     * @test
     * @group url
     *
     */
    public function can_hit_health_check_url()
    {
        // Given
        $this->app['config']->set('app.debug', false);
        $this->app['config']->set('health.checks', [new DebugModeOffHealthCheck()]);

        // When
        $this->get('_health')->assertJson(['health' => 'ok']);

        // Then
    }

    /**
     * @test
     * @group url
     *
     */
    public function return_500_if_health_checks_failed()
    {
        // Given
        $this->app['config']->set('queue.default', 'database');
        $this->app['config']->set('health.checks', [new QueueHealthCheck()]);

        // When
        $this->get('_health')->assertJson(['health' => 'failed']);

        // Then
    }
}