<?php

namespace Modules\Selection\Tests\Unit;

use Modules\Selection\Support\SystemPerformance;
use Tests\TestCase;

class SystemPerformanceTest extends TestCase
{
    public function testAsArray()
    {
        $this->assertEquals(
            [[
                'x' => 0.1,
                'y' => 0.001,
            ], [
                'x' => 1.1,
                'y' => 0.121,
            ], [
                'x' => 2.1,
                'y' => 0.441,
            ], [
                'x' => 3.1,
                'y' => 0.961,
            ], [
                'x' => 4.1,
                'y' => 1.681,
            ], [
                'x' => 5.1,
                'y' => 2.601,
            ], [
                'x' => 6.1,
                'y' => 3.721,
            ], [
                'x' => 7.1,
                'y' => 5.041,
            ], [
                'x' => 8.1,
                'y' => 6.561,
            ], [
                'x' => 9.1,
                'y' => 8.281,
            ], [
                'x' => 10.1,
                'y' => 10.201,
            ], [
                'x' => 11.1,
                'y' => 12.321,
            ]],
            (new SystemPerformance(
                10,
                10,
                11,
                1
            ))->asArray()
        );
    }
}
