<?php


namespace Modules\Pump\Support;

use Modules\Pump\Entities\PumpsAndCoefficients;
use Modules\Selection\Support\PumpPerformance;
use Modules\Selection\Support\Regression;

class PumpCoefficientsHelper
{
    public static function coefficientsForPump($pump, $position)
    {
        $coefficients = $pump->coefficients->firstWhere('position', $position);
        if (!$coefficients) {
            $coefficients = PumpCoefficientsHelper::createdSingle($pump, $position);
        }
        return $coefficients;
    }

    public static function createdSingle($pump, $position)
    {
        $coefficients = Regression::withData(PumpPerformance::by($pump->performance)->lineData($position))->polynomial()->coefficients();
        return PumpsAndCoefficients::create([
            'pump_id' => $pump->id,
            'position' => $position,
            'k' => $coefficients[0],
            'b' => $coefficients[1],
            'c' => $coefficients[2],
        ]);
    }

}
