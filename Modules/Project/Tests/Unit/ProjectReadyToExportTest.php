<?php

namespace Modules\Project\Tests\Unit;

use App\Support\Rates\FakeRates;
use Exception;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\CurveType;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\User;
use Tests\TestCase;

/**
 * @see Project::readyForExport()
 *
 * @author Max Trunnikov
 */
class ProjectReadyToExportTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testReadyToExport()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selections = Selection::factory()->count(3)->create([
            'project_id' => $project->id,
            'pump_id' => Pump::factory()->create([
                'pumpable_type' => Pump::$SINGLE_PUMP,
            ])->id,
        ]);
        $this->actingAs($user);
        $project = $project->readyForExport(
            $selections->pluck('id')->all(),
            new FakeRates()
        )->getAttributes();
        $this->assertArrayHasKey('selections', $project);
        foreach ($project['selections'] as $selection) {
            $this->assertArrayHasKey('pump', $selection);
            $this->assertArrayHasKey('discounted_price', $selection);
            $this->assertArrayHasKey('total_discounted_price', $selection);
            $this->assertArrayHasKey('retail_price', $selection);
            $this->assertArrayHasKey('total_retail_price', $selection);

            $this->assertArrayHasKey('main_curves', $selection);
            $this->assertArrayHasKey('system_performance', $selection['main_curves']);
            $this->assertIsArray($selection['main_curves']['system_performance']);
            $this->assertArrayHasKey('performance_lines', $selection['main_curves']);
            $this->assertIsArray($selection['main_curves']['performance_lines']);
            $this->assertArrayHasKey('dx', $selection['main_curves']);
            $this->assertArrayHasKey('dy', $selection['main_curves']);
            $this->assertArrayHasKey('x_axis_step', $selection['main_curves']);
            $this->assertArrayHasKey('y_axis_step', $selection['main_curves']);
            $this->assertArrayHasKey('width', $selection['main_curves']);
            $this->assertArrayHasKey('height', $selection['main_curves']);
            $this->assertArrayHasKey('working_point', $selection['main_curves']);
            $this->assertArrayHasKey('flow', $selection['main_curves']['working_point']);
            $this->assertArrayHasKey('head', $selection['main_curves']['working_point']);
            $this->assertArrayHasKey('intersection_point', $selection['main_curves']);
            $this->assertArrayHasKey('flow', $selection['main_curves']['intersection_point']);
            $this->assertArrayHasKey('head', $selection['main_curves']['intersection_point']);

            $this->assertArrayHasKey('additional_curves', $selection);
            $this->assertArrayHasKey('dx', $selection['additional_curves']);
            $this->assertArrayHasKey('x_axis_step', $selection['additional_curves']);
            $this->assertArrayHasKey('flow', $selection['additional_curves']);

            foreach (CurveType::cases() as $curveType) {
                $this->assertArrayHasKey($curveType->name, $selection['additional_curves']);
                $this->assertArrayHasKey('lines', $selection['additional_curves'][$curveType->name]);
                $this->assertArrayHasKey('dy', $selection['additional_curves'][$curveType->name]);
                $this->assertArrayHasKey('y_axis_step', $selection['additional_curves'][$curveType->name]);
                $this->assertArrayHasKey('perf_y', $selection['additional_curves'][$curveType->name]);
            }
        }
    }
}
