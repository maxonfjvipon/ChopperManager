<?php

namespace App\Http\Controllers;

use App\Models\ConnectionType;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpRegulation;
use App\Models\Pumps\PumpSeries;
use App\Models\Pumps\PumpType;
use App\Support\Selections\IntersectionPoint;
use App\Support\Selections\Regression;

class SelectionsHelperController extends Controller
{
    protected function inertiaRenderSingleProps($project_id): array
    {
        return [
            'project_id' => $project_id,
            'producers' => PumpProducer::all(),
            'producersWithSeries' => PumpProducer::with(['series' => function ($query) {
                $query->orderBy('name');
            }, 'series.temperatures', 'series.types', 'series.applications', 'series.regulations'])
                ->get(),
//            'applicationIds' => PumpSeries::with('applications')->get(),
            'types' => PumpType::all(),
            'connectionTypes' => ConnectionType::all(),
            'applications' => PumpApplication::all(),
            'phases' => CurrentPhase::all(),
            'dns' => DN::all(),
            'regulations' => PumpRegulation::all(),
            'limitConditions' => LimitCondition::all(),
            'defaults' => [
                'producers' => PumpProducer::whereName('Wilo')->pluck('id')->all(), // todo: default producers
                'regulations' => [PumpRegulation::firstWhere('id', 1)->id]
            ],
        ];
    }

    private function dist($xx)
    {
        $graphicsDist = $xx[count($xx) - 1] - $xx[0];
        $dist = 0;
        if ($graphicsDist < 20) {
            $dist = 0.5;
        }
        if ($graphicsDist >= 20 && $graphicsDist < 50) {
            $dist = 1;
        }
        if ($graphicsDist >= 50 && $graphicsDist < 100) {
            $dist = 2;
        }
        if ($graphicsDist >= 100 && $graphicsDist < 200) {
            $dist = 5;
        }
        if ($graphicsDist >= 200 && $graphicsDist < 500) {
            $dist = 10;
        }
        if ($graphicsDist >= 500) {
            $dist = 20;
        }
        return $dist;
    }

    protected function appropriatedPumpsAndCounts($dbPumps, $mainPumpsCount, $pressure, $consumption, $limit): array
    {
        $appropriatedPumps = [];
        foreach ($dbPumps as $dbPump) {
            $arrayPerformance = $this->arrayPerformance(" ", $dbPump->performance);
            foreach ($mainPumpsCount as $count) {
                $qStart = $arrayPerformance[0] * $count;
                $qEnd = $arrayPerformance[(count($arrayPerformance) - 2)] * $count;
                $intersectionPoint = new IntersectionPoint(
                    (new Regression(
                        $this->lineData($arrayPerformance, $count)
                    ))->polynomial()->coefficients(),
                    $pressure,
                    $consumption
                );
                $x = $intersectionPoint->x();
                $y = $intersectionPoint->y();

                // todo: percentage limit
                if (round($y, 2) >= $pressure - round($pressure * $limit / 100, 2) && $x >= $qStart && $x <= $qEnd) {
                    $appropriatedPumps[] = [
                        'pump' => $dbPump,
                        'count' => $count
                    ];
                }
            }
        }
        return $appropriatedPumps;
    }

    public function arrayPerformance($separator, $performance): array
    {
        return array_map(function ($value) {
            return (float)$value;
        }, explode($separator, $performance));
    }

    public function lineData($arrayPerformance, $count): array
    {
        $data = [];
        for ($i = 0; $i < count($arrayPerformance); $i += 2) {
            $data[] = [
                '0' => $arrayPerformance[$i] * $count,
                '1' => $arrayPerformance[$i + 1]
            ];
        }
        return $data;
    }

    protected function systemPerformance(IntersectionPoint $intersectionPoint, $pressure, $consumption): array
    {
        $systemPerformance = array();
        for ($q = 0.2; $q < $intersectionPoint->x() + 0.4; $q += 0.2) {
            $systemPerformance[] = [
                'x' => round($q, 1),// to fixed 1
                'y' => round($pressure / ($consumption * $consumption) * $q * $q, 1) // to fixed 1
            ];
        }
        return $systemPerformance;
    }

    protected function pumpLine($xx, Regression $regression): array
    {
        $dist = $this->dist($xx);
        $yMax = 0;
        $line = array();
        for ($x = $xx[0]; $x <= $xx[count($xx) - 1]; $x += $dist) {
            $y = $regression->calculatedY($x);
            if ($y > $yMax) {
                $yMax = $y;
            }
            $line[] = [
                'x' => round($x, 1),
                'y' => round($y, 1)
            ];
        }
        if ($line[count($line) - 1]['x'] != $xx[count($xx) - 1]) {
            $y = $regression->calculatedY($xx[count($xx) - 1]);
            $line[] = [
                'x' => round($xx[count($xx) - 1], 1),
                'y' => round($y, 1)
            ];
        }
        return [
            'line' => $line,
            'yMax' => $yMax + 15
        ];
    }
}
