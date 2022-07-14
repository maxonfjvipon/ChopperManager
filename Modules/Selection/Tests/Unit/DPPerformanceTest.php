<?php

namespace Modules\Selection\Tests\Unit;

use Exception;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\DPPerformance;
use Tests\TestCase;

/**
 * @see DPPerformance
 *
 * @author Max Trunnikov
 */
class DPPerformanceTest extends TestCase
{
    /**
     * @return void
     *
     * @throws Exception
     */
    public function testAsArrayAtFirstPosition()
    {
        $this->assertEquals(
            [
                [0.000, 4.195],
                [1.382, 4.054],
                [4.137, 3.742],
                [6.634, 3.435],
                [8.987, 3.125],
                [11.168, 2.814],
                [13.205, 2.507],
                [15.128, 2.196],
                [16.935, 1.889],
                [18.686, 1.576],
                [20.322, 1.268],
                [21.842, 0.964],
                [23.249, 0.660],
                [23.937, 0.498],
            ],
            (new DPPerformance(
                Pump::factory()->create(),
            ))->asArrayAt(1)
        );
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testAsArrayAtSecondPosition()
    {
        $this->assertEquals(
            [
                [0.000, 4.202],
                [2.714, 4.086],
                [8.981, 3.749],
                [15.249, 3.379],
                [21.455, 2.989],
                [27.231, 2.586],
                [32.515, 2.178],
                [37.492, 1.762],
                [42.162, 1.354],
                [46.648, 0.949],
                [50.089, 0.626],
                [51.381, 0.496],
            ],
            (new DPPerformance(
                Pump::factory()->create(),
            ))->asArrayAt(2)
        );
    }
}
