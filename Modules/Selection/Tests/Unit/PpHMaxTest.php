<?php

namespace Modules\Selection\Tests\Unit;

use Exception;
use Modules\Selection\Support\Performance\FakePerformance;
use Modules\Selection\Support\Performance\PpHMax;
use Tests\TestCase;

/**
 * @see PpHMax
 *
 * @author Max Trunnikov
 */
class PpHMaxTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAsNumberLessThanHead()
    {
        $this->assertEquals(
            5.5,
            (new PpHMax(
                new FakePerformance([
                    [1, 2],
                    [2, 3],
                    [3, 5], // <-- 5 is max, 5 * 0.1 = 5.5
                    [4, 4],
                ]), 3
            ))->asNumber()
        );
    }

    /**
     * @throws Exception
     */
    public function testAsNumberMoreThanHead()
    {
        $this->assertEquals(
            13.2,
            (new PpHMax(
                new FakePerformance([
                    [1, 2],
                    [2, 3],
                    [3, 5],
                    [4, 4],
                ]), 12 // <-- 12 is max, 12 * 0.1 = 5.5
            ))->asNumber()
        );
    }
}
