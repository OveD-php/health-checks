<?php


use Illuminate\Http\Response;
use Orchestra\Testbench\TestCase;
use Vistik\Stats\ResponseCounter;

class StatsCounterTest extends TestCase
{
    /**
     * @test
     * @group stats
     *
     */
    public function can_get_ratio()
    {
        // Given
        $mock200 = Mockery::mock(Response::class);
        $mock200->shouldReceive('getStatusCode')->andReturn(200);

        $this->assertEquals(0, ResponseCounter::getRatio(200));

        $mock500 = Mockery::mock(Response::class);
        $mock500->shouldReceive('getStatusCode')->andReturn(500);
        ResponseCounter::addResponse($mock500);

        // When
        for ($i = 0; $i <= 10; $i++) {
            ResponseCounter::addResponse($mock200);
        }

        // Then
        $this->assertEquals(91.66666666666666, ResponseCounter::getRatio(200));
    }

    /**
     * @test
     * @group stats
     *
     */
    public function can_count_total_and_by_status_code()
    {
        // Given
        $mock200 = Mockery::mock(Response::class);
        $mock200->shouldReceive('getStatusCode')->andReturn(200);

        $this->assertEquals(0, ResponseCounter::getRatio(200));
        $this->assertEquals(0, ResponseCounter::getTotalCount());

        $mock500 = Mockery::mock(Response::class);
        $mock500->shouldReceive('getStatusCode')->andReturn(500);
        ResponseCounter::addResponse($mock500);

        // When
        for ($i = 0; $i < 10; $i++) {
            ResponseCounter::addResponse($mock200);
        }

        // Then
        $this->assertEquals(11, ResponseCounter::getTotalCount());
        $this->assertEquals(10, ResponseCounter::getCount(200));
        $this->assertEquals(1, ResponseCounter::getCount(500));
    }
}