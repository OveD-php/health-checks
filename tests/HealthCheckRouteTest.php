<?php

use Orchestra\Testbench\TestCase;
use Vistik\Checks\DebugModeOff;
use Vistik\Checks\QueueProcessing;
use Vistik\ServiceProvider\HealthCheckServiceProvider;

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
        $this->app['config']->set('health.checks', [new DebugModeOff()]);
        $this->app['config']->set('health.route.enabled', true);

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
        $this->app['config']->set('health.checks', [new QueueProcessing()]);
        $this->app['config']->set('health.route.enabled', true);

        // When
        $this->get('_health')->assertJson(['health' => 'failed'])->assertStatus(500);

        // Then
    }

    /**
     * @test
     * @group url
     *
     */
    public function health_url_returns_404_if_disabled()
    {
        // Given
        $this->app['config']->set('health.route.enabled', false);

        // When
        $response = $this->get('_health');

        // Then
        $response->assertSee("Route not found");
        $response->assertStatus(404);

    }
}