<?php

namespace Modules\Pump\Tests\Unit;

use Modules\Pump\Entities\Pump;
use Tests\TestCase;

class PumpCoefficientsCountTest extends TestCase
{
    public function test_coefficients_count()
    {
        $this->assertEquals(
            9,
            Pump::factory()->create(['pumpable_type' =>Pump::$SINGLE_PUMP])->totalPossibleCurvesCount()
        );
        $this->assertEquals(
            2,
            Pump::factory()->create(['pumpable_type' =>Pump::$DOUBLE_PUMP])->totalPossibleCurvesCount()
        );
    }
}
