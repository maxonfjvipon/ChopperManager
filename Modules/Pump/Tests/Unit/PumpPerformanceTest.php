<?php

namespace Modules\Pump\Tests\Unit;

use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\DPPerformance;
use Modules\Selection\Support\Performance\SPPerformance;
use Tests\TestCase;

/**
 * @see Pump::performance()
 *
 * @author Max Trunnikov
 */
class PumpPerformanceTest extends TestCase
{
    /**
     * @return void
     */
    public function testPerfForSinglePump()
    {
        $this->assertInstanceOf(
            SPPerformance::class,
            Pump::factory()->create(['pumpable_type' => Pump::$SINGLE_PUMP])
                ->performance()
        );
    }

    /**
     * @return void
     */
    public function testPerfForDoublePump()
    {
        $this->assertInstanceOf(
            DPPerformance::class,
            Pump::factory()->create(['pumpable_type' => Pump::$DOUBLE_PUMP])
                ->performance()
        );
    }
}
