<?php

namespace Modules\Selection\Tests\Unit;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Selection\Support\Performance\FakePerformance;
use Modules\Selection\Support\Performance\PpQEnd;
use Tests\TestCase;

/**
 * @see PpQEnd
 *
 * @author Max Trunnikov
 */
class PpQEndTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testAsNumberAtRandomPosition()
    {
        $num = $this->faker->numberBetween(2, 9);
        $atPos = $this->faker->randomNumber();
        $this->assertEquals(
            $atPos * $num,
            (new PpQEnd(
                new FakePerformance(
                    [
                        [1, 2],
                        [3, 4],
                        [4, 5],
                        [$atPos, 6],
                    ]
                ),
                $num
            ))->asNumber()
        );
    }
}
