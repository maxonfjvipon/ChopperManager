<?php

namespace Modules\Selection\Tests\Unit;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Performance\SPPerformance;
use Tests\TestCase;

/**
 * @see SPPerformance
 *
 * @author Max Trunnikov
 */
class SPPerformanceTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testAsArrayAtFirst()
    {
        $this->assertEquals(
            [
                [0, 5.2],
                [1, 4.78],
                [1.6, 4.42],
                [2.2, 3.97],
                [3, 3.28],
                [3.6, 2.6],
                [4, 2.13],
                [4.8, 1.16],
                [5.44, 0.37],
            ],
            (new SPPerformance(
                Pump::factory()->create()
            ))->asArrayAt(1)
        );
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testAsArrayAtNotFirst()
    {
        $num = $this->faker->numberBetween(2, 9);
        $this->assertEquals(
            [
                [0 * $num, 5.2],
                [1 * $num, 4.78],
                [1.6 * $num, 4.42],
                [2.2 * $num, 3.97],
                [3 * $num, 3.28],
                [3.6 * $num, 2.6],
                [4 * $num, 2.13],
                [4.8 * $num, 1.16],
                [5.44 * $num, 0.37],
            ],
            (new SPPerformance(
                Pump::factory()->create()
            ))->asArrayAt($num)
        );
    }
}
