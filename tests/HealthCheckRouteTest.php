<?php

use Carbon\Carbon;
use Illuminate\Http\Response;
use Orchestra\Testbench\TestCase;
use Vistik\Checks\Environment\DebugModeOff;
use Vistik\Checks\Queue\QueueIsProcessing;
use Vistik\Metrics\Metrics;
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
        $this->app['config']->set('health.checks', [new QueueIsProcessing()]);
        $this->app['config']->set('health.route.enabled', true);

        // When
        $this
            ->get('_health')
            ->assertJson(['health' => 'failed'])
            ->assertStatus(500);

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

    /**
     * @test
     * @group url
     *
     */
    public function can_hit_health_stats_url()
    {
        // Given
        $this->app['config']->set('health.route.enabled', true);

        // When
        $response = $this->get('_health/stats');

        // Then
        $response->assertSee('{"200":{"count":0,"ratio":0},');
        $response->assertStatus(200);
    }

    /**
     * @test
     * @group url
     */
    public function will_return_timestamp_on_stats()
    {
        // Given
        $this->app['config']->set('health.route.enabled', true);

        $mock = Mockery::mock(Response::class);
        $mock->shouldReceive('getStatusCode')->andReturn(200);
        Metrics::trackResponse($mock);

        // When
        $response = $this->get('_health/stats');

        // Then
        $now = Carbon::now()->toDateTimeString();
        $nowPlus60Min = Carbon::now()->addMinute(60)->toDateTimeString();
        $response->assertSee('"from_timestamp":"' . $now);
        $response->assertSee('"to_timestamp":"' . $nowPlus60Min);
        $response->assertStatus(200);

        sleep(1);

        $response = $this->get('_health/stats');

        // Then
        $response->assertSee('"from_timestamp":"' . $now);
        $response->assertSee('"to_timestamp":"' . $nowPlus60Min);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @group url
     */
    public function will_return_avg_response_time()
    {
        // Given
        $this->app['config']->set('health.route.enabled', true);

        $mock = Mockery::mock(Response::class);
        $mock->shouldReceive('getStatusCode')->andReturn(200);
        Metrics::trackResponse($mock);

        // When
        $response = $this->get('_health/stats');

        // Then
        $now = Carbon::now()->toDateTimeString();
        $nowPlus60Min = Carbon::now()->addMinute(60)->toDateTimeString();
        $response->assertSee('"from_timestamp":"' . $now);
        $response->assertSee('"to_timestamp":"' . $nowPlus60Min);
        $response->assertStatus(200);

        sleep(1);

        $response = $this->get('_health/stats');

        // Then
        $response->assertSee('"from_timestamp":"' . $now);
        $response->assertSee('"to_timestamp":"' . $nowPlus60Min);

        $response->assertStatus(200);
    }
}