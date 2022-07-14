<?php

namespace Modules\Selection\Tests\Unit;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Selection\Support\Performance\FakePerformance;
use Modules\Selection\Support\Performance\PpQStart;
use Tests\TestCase;

class PpQStartTest extends TestCase
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
            (new PpQStart(
                new FakePerformance(
                    [
                        [$atPos, 2],
                        [3, 4],
                        [4, 5],
                        [10, 6],
                    ]
                ),
                $num
            ))->asNumber()
        );
    }
}
