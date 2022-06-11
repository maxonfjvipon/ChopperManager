<?php

namespace Modules\Selection\Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Modules\Project\Entities\Project;
use Modules\Pump\Database\factories\PumpFactory;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\PumpSeries\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpCategory;
use Modules\Pump\Entities\PumpCoefficients;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Pump\Entities\PumpsPriceList;
use Modules\Selection\Database\factories\SelectionFactory;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Http\Endpoints\EpCreateSelection;
use Modules\Selection\Http\Endpoints\EpPumpStationCurves;
use Modules\Selection\Http\Endpoints\EpSelectionsDashboard;
use Modules\Selection\Http\Endpoints\EpDestroySelection;
use Modules\Selection\Http\Endpoints\EpExportSelectionAtOnce;
use Modules\Selection\Http\Endpoints\EpExportSelection;
use Modules\Selection\Http\Endpoints\EpRestoreSelection;
use Modules\Selection\Http\Endpoints\EpMakeSelection;
use Modules\Selection\Http\Endpoints\EpShowSelection;
use Modules\Selection\Http\Endpoints\EpStoreSelection;
use Modules\Selection\Http\Endpoints\EpUpdateSelection;
use Modules\Selection\Http\Requests\RqPumpStationCurves;
use Modules\Selection\Http\Requests\DoublePump\RqDoublePumpSelectionCurves;
use Modules\Selection\Http\Requests\DoublePump\RqExportDoublePumpSelectionAtOnce;
use Modules\Selection\Http\Requests\DoublePump\RqMakeDoublePumpSelection;
use Modules\Selection\Http\Requests\DoublePump\RqStoreDoublePumpSelection;
use Modules\Selection\Http\Requests\RqExportAtOnceSelection;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Http\Requests\RqStoreSelection;
use Modules\Selection\Http\Requests\SinglePump\RqSinglePumpSelectionCurves;
use Modules\Selection\Http\Requests\SinglePump\RqExportSinglePumpSelectionAtOnce;
use Modules\Selection\Http\Requests\SinglePump\RqMakeSinglePumpSelection;
use Modules\Selection\Http\Requests\SinglePump\RqStoreSinglePumpSelection;
use Modules\Selection\Transformers\SelectionResources\WaterHandleSelectionAsResource;
use Modules\Selection\Transformers\SelectionResources\WaterAutoSelectionAsResource;
use Modules\User\Entities\User;
use Tests\TestCase;

class SelectionEndpointsTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     * @see EpSelectionsDashboard
     * @author Max Trunnikov
     */
    public function test_selections_dashboard()
    {
        $this->actingAs(User::fakeWithRole())
            ->get(route('selections.dashboard', 2))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Selection::Dashboard', false)
                ->where('project_id', 2)
                ->count('selection_types', 2) // two types are available for every user by default
                ->has('selection_types.0', fn(AssertableInertia $page) => $page
                    ->has('name')
                    ->has('pumpable_type')
                    ->has('img')
                )
            );
    }

    private function assertSelectionProps(AssertableInertia $page)
    {
        return $page->has('selection_props', fn(AssertableInertia $page) => $page
            ->has('brands_with_series')
            ->has('brands_with_series.0', fn(AssertableInertia $page) => $page
                ->has('series')
                ->has('series.0.types')
                ->has('series.0.applications')
                ->has('series.0.power_adjustment')
                ->has('series.0.image')
                ->etc()
            )
            ->has('types')
            ->has('applications')
            ->has('connection_types')
            ->count('connection_types', MainsConnection::allOrCached()->count())
            ->has('mains_connections')
            ->count('mains_connections', MainsConnection::allOrCached()->count())
            ->has('dns')
            ->count('dns', DN::allOrCached()->count())
            ->has('power_adjustments')
            ->count('power_adjustments', ElPowerAdjustment::allOrCached()->count())
            ->has('limit_conditions')
            ->count('limit_conditions', LimitCondition::allOrCached()->count())
            ->has('selection_ranges')
            ->count('selection_ranges', SelectionRange::allOrCached()->count())
            ->has('work_schemes')
            ->count('work_schemes', DoublePumpWorkScheme::allOrCached()->count())
            ->has('defaults', fn(AssertableInertia $page) => $page
                ->has('brands')
                ->count('brands', 0)
                ->has('power_adjustments')
                ->count('power_adjustments', 1)
                ->where('power_adjustments.0', 2)
            )
        );
    }

    /**
     * @return void
     * @see EpCreateSelection
     * @author Max Trunnikov
     */
    public function test_selections_create_endpoint_without_saving()
    {
        PumpSeries::factory()->count(5)->create(['category_id' => PumpCategory::$SINGLE_PUMP]);
        $this->actingAs(User::fakeWithRole())
            ->get(route('projects.selections.create', [
                'project' => '-1',
                'pumpable_type' => Pump::$SINGLE_PUMP
            ]))
            ->assertStatus(200)
            ->assertInertia(fn(AssertableInertia $page) => $this->assertSelectionProps($page)
                ->component('Selection::SinglePump', false)
                ->where('project_id', '-1')
            );
    }

    /**
     * @return void
     * @see EpCreateSelection
     * @author Max Trunnikov
     */
    public function test_selections_create_endpoint()
    {
        PumpSeries::factory()->count(5)->create(['category_id' => PumpCategory::$DOUBLE_PUMP]);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $this->actingAs($user)
            ->get(route('projects.selections.create', [
                'project' => $project->id,
                'pumpable_type' => Pump::$DOUBLE_PUMP
            ]))
            ->assertStatus(200)
            ->assertInertia(fn(AssertableInertia $page) => $this->assertSelectionProps($page)
                ->component('Selection::DoublePump', false)
                ->where('project_id', (string)$project->id)
            );
    }

    /**
     * @return void
     * @see EpShowSelection
     * @see WaterAutoSelectionAsResource
     * @author Max Trunnikov
     */
    public function test_show_single_pump_selection()
    {
        $series = PumpSeries::factory()->count(5)->create(['category_id' => PumpCategory::$SINGLE_PUMP]);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$SINGLE_PUMP, 'series_id' => $series->first()->id]);
        $selection = Selection::factory()->create([
            'project_id' => $project->id,
            'pump_id' => $pump->id
        ]);
        $this->actingAs($user)
            ->get(route('selections.show', [
                'selection' => $selection->id,
                'pumpable_type' => Pump::$SINGLE_PUMP
            ]))
            ->assertInertia(fn(AssertableInertia $page) => $this->assertSelectionProps($page)
                ->component('Selection::SinglePump', false)
                ->where('project_id', $selection->project_id)
                ->has('selection', fn(AssertableInertia $page) => $page
                    ->has('data', fn(AssertableInertia $page) => $page
                        ->whereAll([
                            'id' => $selection->id,
                            'head' => $selection->head,
                            'flow' => $selection->flow,
                            'fluid_temperature' => $selection->fluid_temperature,
                            'deviation' => $selection->deviation,
                            'reserve_pumps_count' => $selection->reserve_pumps_count,
                            'selected_pump_name' => $selection->selected_pump_name,
                            'use_additional_filters' => $selection->use_additional_filters,
                            'range_id' => $selection->range_id,

                            'power_limit_checked' => $selection->power_limit_checked,
                            'power_limit_condition_id' => $selection->power_limit_condition_id,
                            'power_limit_value' => $selection->power_limit_value,

                            'ptp_length_limit_checked' => $selection->ptp_length_limit_checked,
                            'ptp_length_limit_condition_id' => $selection->ptp_length_limit_condition_id,
                            'ptp_length_limit_value' => $selection->ptp_length_limit_value,

                            'dn_suction_limit_checked' => $selection->dn_suction_limit_checked,
                            'dn_suction_limit_condition_id' => $selection->dn_suction_limit_condition_id,
                            'dn_suction_limit_id' => $selection->dn_suction_limit_id,

                            'dn_pressure_limit_checked' => $selection->dn_pressure_limit_checked,
                            'dn_pressure_limit_condition_id' => $selection->dn_pressure_limit_condition_id,
                            'dn_pressure_limit_id' => $selection->dn_pressure_limit_id,

                            /** @see SelectionFactory */
                            'connection_types' => [1, 2],
                            'mains_connections' => [1, 2],
                            'main_pumps_counts' => [1, 2, 3],
                            'pump_brands' => [1, 2, 3],
                            'power_adjustments' => [1, 2],
                            'pump_types' => [1, 2],
                            'pump_applications' => [1, 2],
                            'pump_series' => [1, 2],
                            'custom_range' => [0, 100],

                            'to_show' => [
                                'name' => $selection->selected_pump_name,
                                'pump_id' => $selection->pump_id,
                                'pump_type' => $selection->pump_type,
                                'pumps_count' => $selection->pumps_count,
                                'main_pumps_count' => $selection->pumps_count - $selection->reserve_pumps_count,
                                'flow' => $selection->flow,
                                'head' => $selection->head,
                                'fluid_temperature' => $selection->fluid_temperature,
                            ],
                        ])
                    )
                )
            );
    }

    /**
     * @return void
     * @see EpShowSelection
     * @see WaterHandleSelectionAsResource
     * @author Max Trunnikov
     */
    public function test_show_double_pump_selection()
    {
        $series = PumpSeries::factory()->count(5)->create(['category_id' => PumpCategory::$DOUBLE_PUMP]);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$DOUBLE_PUMP, 'series_id' => $series->first()->id]);
        $selection = Selection::factory()->create([
            'project_id' => $project->id,
            'pump_id' => $pump->id
        ]);
        $this->actingAs($user)
            ->get(route('selections.show', [
                'selection' => $selection->id,
                'pumpable_type' => Pump::$DOUBLE_PUMP
            ]))
            ->assertInertia(fn(AssertableInertia $page) => $this->assertSelectionProps($page)
                ->component('Selection::DoublePump', false)
                ->where('project_id', $selection->project_id)
                ->has('selection', fn(AssertableInertia $page) => $page
                    ->has('data', fn(AssertableInertia $page) => $page
                        ->whereAll([
                            'id' => $selection->id,
                            'head' => $selection->head,
                            'flow' => $selection->flow,
                            'fluid_temperature' => $selection->fluid_temperature,
                            'deviation' => (float)$selection->deviation,
                            'selected_pump_name' => $selection->selected_pump_name,
                            'use_additional_filters' => $selection->use_additional_filters,
                            'range_id' => $selection->range_id,
                            'dp_work_scheme_id' => $selection->dp_work_scheme_id,

                            'power_limit_checked' => $selection->power_limit_checked,
                            'power_limit_condition_id' => $selection->power_limit_condition_id,
                            'power_limit_value' => $selection->power_limit_value,

                            'ptp_length_limit_checked' => $selection->ptp_length_limit_checked,
                            'ptp_length_limit_condition_id' => $selection->ptp_length_limit_condition_id,
                            'ptp_length_limit_value' => $selection->ptp_length_limit_value,

                            'dn_suction_limit_checked' => $selection->dn_suction_limit_checked,
                            'dn_suction_limit_condition_id' => $selection->dn_suction_limit_condition_id,
                            'dn_suction_limit_id' => $selection->dn_suction_limit_id,

                            'dn_pressure_limit_checked' => $selection->dn_pressure_limit_checked,
                            'dn_pressure_limit_condition_id' => $selection->dn_pressure_limit_condition_id,
                            'dn_pressure_limit_id' => $selection->dn_pressure_limit_id,

                            /** @see SelectionFactory */
                            'connection_types' => [1, 2],
                            'power_adjustments' => [1, 2],
                            'pump_series' => [1, 2],
                            'pump_brands' => [1, 2, 3],
                            'mains_connections' => [1, 2],
                            'pump_types' => [1, 2],
                            'custom_range' => [0, 100],

                            'to_show' => [
                                'name' => $selection->selected_pump_name,
                                'pump_id' => $selection->pump_id,
                                'flow' => $selection->flow,
                                'head' => $selection->head,
                                'fluid_temperature' => $selection->fluid_temperature,
                                'dp_work_scheme_id' => $selection->dp_work_scheme_id
                            ],
                        ])
                    )
                )
            );
    }

    /**
     * @return void
     * @see EpRestoreSelection
     * @author Max Trunnikov
     */
    public function test_selection_restore()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection = Selection::fakeForProject($project);
        $selection->delete();
        $this->assertSoftDeleted($selection);
        $this->actingAs($user)
            ->from(route('projects.show', $project->id))
            ->get(route('selections.restore', $selection->id))
            ->assertStatus(302)
            ->assertRedirect(route('projects.show', $project->id));
        $this->assertNotSoftDeleted($selection);
    }

    /**
     * @return void
     * @see EpDestroySelection
     * @author Max Trunnikov
     */
    public function test_selection_destroy()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection = Selection::fakeForProject($project);
        $this->assertNotSoftDeleted($selection);
        $this->actingAs($user)
            ->from(route('projects.show', $project->id))
            ->delete(route('selections.destroy', $selection->id), [
                '_token' => csrf_token()
            ])
            ->assertStatus(302)
            ->assertRedirect(route('projects.show', $project->id));
        $this->assertSoftDeleted($selection);
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpStoreSelection
     * @see RqStoreSinglePumpSelection
     */
    public function test_single_pump_selection_store()
    {
        $this->app->bind(RqStoreSelection::class, RqStoreSinglePumpSelection::class);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selectionData = [
            'pump_id' => Pump::factory()->create()->id,
            'selected_pump_name' => $this->faker->word(),
            'pumps_count' => $this->faker->numberBetween(3, 9),
            'flow' => $this->faker->randomFloat(3, 0, 1000),
            'head' => $this->faker->randomFloat(3, 0, 1000),
            'fluid_temperature' => $this->faker->randomFloat(2, -30, 200),
            'deviation' => $this->faker->randomFloat(1, -50, 50),
            'reserve_pumps_count' => $this->faker->numberBetween(1, 2),
            'range_id' => SelectionRange::allOrCached()->random()->id,
            'use_additional_filters' => true,

            'power_limit_checked' => true,
            'power_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'power_limit_value' => $this->faker->randomNumber(),

            'ptp_length_limit_checked' => true,
            'ptp_length_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'ptp_length_limit_value' => $this->faker->randomNumber(),

            'dn_suction_limit_checked' => true,
            'dn_suction_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_suction_limit_id' => DN::allOrCached()->random()->id,

            'dn_pressure_limit_checked' => true,
            'dn_pressure_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_pressure_limit_id' => DN::allOrCached()->random()->id,
        ];
        $addData = [
            'custom_range' => [0, 100],
            'connection_type_ids' => [1, 2],
            'mains_connection_ids' => [1, 2],
            'main_pumps_counts' => [1, 2, 3],
            'pump_brand_ids' => [1, 2, 3],
            'power_adjustment_ids' => [1, 2],
            'pump_type_ids' => [1, 2],
            'pump_application_ids' => [1, 2],
            'pump_series_ids' => [1, 2]
        ];
        $this->startSession()
            ->from(route('projects.selections.create', $project->id))
            ->actingAs($user)
            ->post(route('projects.selections.store', $project->id), array_merge(
                [
                    '_token' => csrf_token(),
                    'pumpable_type' => Pump::$SINGLE_PUMP
                ],
                $selectionData,
                $addData
            ))
            ->assertStatus(302)
            ->assertRedirect(route('projects.selections.create', $project->id));

        $this->assertDatabaseHas('selections', array_merge(
            [
                'project_id' => $project->id,
                'dp_work_scheme_id' => null,
            ],
            $selectionData,
            array_map(fn($item) => implode(",", $item), $addData)
        ));
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpStoreSelection
     * @see RqStoreDoublePumpSelection
     */
    public function test_double_pump_selection_store()
    {
        $this->app->bind(RqStoreSelection::class, RqStoreDoublePumpSelection::class);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selectionData = [
            'pump_id' => Pump::factory()->create()->id,
            'selected_pump_name' => $this->faker->word(),
            'dp_work_scheme_id' => DoublePumpWorkScheme::allOrCached()->random()->id,

            'flow' => $this->faker->randomFloat(3, 0, 1000),
            'head' => $this->faker->randomFloat(3, 0, 1000),
            'fluid_temperature' => $this->faker->randomFloat(2, -30, 200),
            'deviation' => $this->faker->randomFloat(1, -50, 50),
            'range_id' => SelectionRange::allOrCached()->random()->id,
            'use_additional_filters' => true,

            'power_limit_checked' => true,
            'power_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'power_limit_value' => $this->faker->randomNumber(),

            'ptp_length_limit_checked' => true,
            'ptp_length_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'ptp_length_limit_value' => $this->faker->randomNumber(),

            'dn_suction_limit_checked' => true,
            'dn_suction_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_suction_limit_id' => DN::allOrCached()->random()->id,

            'dn_pressure_limit_checked' => true,
            'dn_pressure_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_pressure_limit_id' => DN::allOrCached()->random()->id,
        ];
        $addData = [
            'custom_range' => [0, 100],
            'pump_series_ids' => [1, 2],
            'pump_brand_ids' => [1, 2, 3],
            'mains_connection_ids' => [1, 2],
            'pump_type_ids' => [1, 2],
            'connection_type_ids' => [1, 2],
            'power_adjustment_ids' => [1, 2],
        ];
        $this->startSession()
            ->from(route('projects.selections.create', $project->id))
            ->actingAs($user)
            ->post(route('projects.selections.store', $project->id), array_merge(
                [
                    '_token' => csrf_token(),
                    'pumpable_type' => Pump::$DOUBLE_PUMP
                ],
                $selectionData,
                $addData
            ))
            ->assertStatus(302)
            ->assertRedirect(route('projects.selections.create', $project->id));

        $this->assertDatabaseHas('selections', array_merge(
            [
                'project_id' => $project->id,
                'pump_application_ids' => null,
                'reserve_pumps_count' => null,
                'pumps_count' => null,
                'main_pumps_counts' => null
            ],
            $selectionData,
            array_map(fn($item) => implode(",", $item), $addData)
        ));
    }

    /**
     * @return void
     * @throws Exception
     * @see RqSinglePumpSelectionCurves
     * @see PumpFactory sp_performance is the same TOP-S 25/5 EM PN6/10 has
     * @author Max Trunnikov
     * @see EpPumpStationCurves
     */
    public function test_selection_curves_for_single_pump()
    {
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$SINGLE_PUMP]);
        $this->app->bind(RqPumpStationCurves::class, RqSinglePumpSelectionCurves::class);
        $this->startSession()
            ->from(route('projects.selections.create', "-1"))
            ->actingAs(User::fakeWithRole())
            ->post(route('selections.curves'), [
                '_token' => csrf_token(),
                'pumps_count' => 3,
                'main_pumps_count' => 2,
                'pump_id' => $pump->id,
                'pumpable_type' => Pump::$SINGLE_PUMP,
                'head' => 4,
                'flow' => 3,
            ])
            ->assertSee("svg")
            ->assertStatus(200);
    }

    /**
     * @return void
     * @throws Exception
     * @see RqDoublePumpSelectionCurves
     * @see PumpFactory dp_standby/peak_performance is the same DCM-G 65-420/A/BAQE/0,25 has
     * @author Max Trunnikov
     * @see EpPumpStationCurves
     */
    public function test_selection_curves_for_double_pump()
    {
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$DOUBLE_PUMP]);
        $this->app->bind(RqPumpStationCurves::class, RqDoublePumpSelectionCurves::class);
        $this->startSession()
            ->from(route('projects.selections.create', "-1"))
            ->actingAs(User::fakeWithRole())
            ->post(route('selections.curves'), [
                '_token' => csrf_token(),
                'pump_id' => $pump->id,
                'pumpable_type' => Pump::$DOUBLE_PUMP,
                'dp_work_scheme_id' => DoublePumpWorkScheme::allOrCached()->random()->id,
                'head' => 4,
                'flow' => 3,
            ])
            ->assertSee('svg')
            ->assertStatus(200);
    }

    /**
     * @return void
     * @throws Exception
     * @see EpUpdateSelection
     * @see RqStoreSinglePumpSelection
     * @author Max Trunnikov
     */
    public function test_update_single_pump_election()
    {
        $this->app->bind(RqStoreSelection::class, RqStoreSinglePumpSelection::class);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection = Selection::fakeForProject($project);
        $selectionData = [
            'pump_id' => Pump::factory()->create()->id,
            'selected_pump_name' => $this->faker->word(),
            'pumps_count' => $this->faker->numberBetween(3, 9),
            'flow' => $this->faker->randomFloat(3, 0, 1000),
            'head' => $this->faker->randomFloat(3, 0, 1000),
            'fluid_temperature' => $this->faker->randomFloat(2, -30, 200),
            'deviation' => $this->faker->randomFloat(1, -50, 50),
            'reserve_pumps_count' => $this->faker->numberBetween(1, 2),
            'range_id' => SelectionRange::allOrCached()->random()->id,
            'use_additional_filters' => true,

            'power_limit_checked' => true,
            'power_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'power_limit_value' => $this->faker->randomNumber(),

            'ptp_length_limit_checked' => true,
            'ptp_length_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'ptp_length_limit_value' => $this->faker->randomNumber(),

            'dn_suction_limit_checked' => true,
            'dn_suction_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_suction_limit_id' => DN::allOrCached()->random()->id,

            'dn_pressure_limit_checked' => true,
            'dn_pressure_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_pressure_limit_id' => DN::allOrCached()->random()->id,
        ];
        $addData = [
            'custom_range' => [1, 99],
            'connection_type_ids' => [1],
            'mains_connection_ids' => [2],
            'main_pumps_counts' => [1, 3],
            'pump_brand_ids' => [3],
            'power_adjustment_ids' => [1],
            'pump_type_ids' => [2],
            'pump_application_ids' => [3, 5],
            'pump_series_ids' => [3, 2]
        ];
        $this->startSession()
            ->from(route('selections.show', $selection->id))
            ->actingAs($user)
            ->put(route('selections.update', $selection->id), array_merge(
                [
                    '_token' => csrf_token(),
                    'pumpable_type' => Pump::$SINGLE_PUMP

                ],
                $selectionData,
                $addData
            ))
            ->assertStatus(302)
            ->assertRedirect(route('projects.show', $project->id));
        $this->assertDatabaseHas('selections', array_merge(
            [
                'id' => $selection->id,
                'project_id' => $project->id,
            ],
            $selectionData,
            array_map(fn($item) => implode(",", $item), $addData)
        ));
    }

    /**
     * @return void
     * @throws Exception
     * @see EpUpdateSelection
     * @see RqStoreSinglePumpSelection
     * @author Max Trunnikov
     */
    public function test_update_double_pump_election()
    {
        $this->app->bind(RqStoreSelection::class, RqStoreDoublePumpSelection::class);
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection = Selection::fakeForProject($project);
        $selectionData = [
            'pump_id' => Pump::factory()->create()->id,
            'selected_pump_name' => $this->faker->word(),
            'dp_work_scheme_id' => DoublePumpWorkScheme::allOrCached()->random()->id,

            'flow' => $this->faker->randomFloat(3, 0, 1000),
            'head' => $this->faker->randomFloat(3, 0, 1000),
            'fluid_temperature' => $this->faker->randomFloat(2, -30, 200),
            'deviation' => $this->faker->randomFloat(1, -50, 50),
            'range_id' => SelectionRange::allOrCached()->random()->id,
            'use_additional_filters' => true,

            'power_limit_checked' => true,
            'power_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'power_limit_value' => $this->faker->randomNumber(),

            'ptp_length_limit_checked' => true,
            'ptp_length_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'ptp_length_limit_value' => $this->faker->randomNumber(),

            'dn_suction_limit_checked' => true,
            'dn_suction_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_suction_limit_id' => DN::allOrCached()->random()->id,

            'dn_pressure_limit_checked' => true,
            'dn_pressure_limit_condition_id' => LimitCondition::allOrCached()->random()->id,
            'dn_pressure_limit_id' => DN::allOrCached()->random()->id,
        ];
        $addData = [
            'custom_range' => [10, 80],
            'pump_series_ids' => [3, 1],
            'pump_brand_ids' => [5, 2],
            'mains_connection_ids' => [1],
            'pump_type_ids' => [8, 10],
            'connection_type_ids' => [2],
            'power_adjustment_ids' => [1],
        ];
        $this->startSession()
            ->from(route('selections.show', $selection->id))
            ->actingAs($user)
            ->put(route('selections.update', $selection->id), array_merge(
                [
                    '_token' => csrf_token(),
                    'pumpable_type' => Pump::$DOUBLE_PUMP

                ],
                $selectionData,
                $addData
            ))
            ->assertStatus(302)
            ->assertRedirect(route('projects.show', $project->id));
        $this->assertDatabaseHas('selections', array_merge(
            [
                'id' => $selection->id,
                'project_id' => $project->id,
            ],
            $selectionData,
            array_map(fn($item) => implode(",", $item), $addData)
        ));
    }

    /**
     * @return void
     * @see EpMakeSelection
     * @see PumpFactory sp_performance is the same TOP-S 25/5 EM PN6/10 has
     * @author Max Trunnikov
     */
    public function test_select_single_pumps(): void
    {
        $this->app->bind(RqMakeSelection::class, RqMakeSinglePumpSelection::class);
        $brand1 = PumpBrand::factory()->create();
        $brand2 = PumpBrand::factory()->create();
        $series1 = PumpSeries::factory()->count(2)->create(['brand_id' => $brand1->id]);
        $series2 = PumpSeries::factory()->count(2)->create(['brand_id' => $brand2->id]);
        foreach ($series1 as $series) {
            Pump::factory()->count(5)->create(['series_id' => $series->id, 'pumpable_type' => Pump::$SINGLE_PUMP]);
        }
        // will be not selected
        foreach ($series2 as $series) {
            Pump::factory()->count(5)->create([
                'series_id' => $series->id,
                'pumpable_type' => Pump::$DOUBLE_PUMP,
            ]);
        }
        $user = User::fakeWithRole();
        $data = [
            "pump_brand_ids" => [$brand1->id, $brand2->id],
            "fluid_temperature" => 20,
            "power_adjustment_ids" => [2],
            "flow" => 3,
            "head" => 4,
            "main_pumps_counts" => [1, 2],
            "reserve_pumps_count" => 1,
            "range_id" => 3,
            "custom_range" => [0, 100],
            "power_limit_checked" => false,
            "power_limit_condition_id" => null,
            "ptp_length_limit_checked" => false,
            "ptp_length_limit_condition_id" => null,
            "dn_suction_limit_checked" => false,
            "dn_suction_limit_condition_id" => null,
            "dn_pressure_limit_checked" => false,
            "dn_pressure_limit_condition_id" => null,
            "use_additional_filters" => true,
            "series_ids" => $series1->merge($series2)->pluck('id')->all(),
            "pumpable_type" => Pump::$SINGLE_PUMP
        ];
        $this->startSession()
            ->from(route('projects.selections.create', "-1"))
            ->actingAs($user)
            ->post(route('selections.select'), array_merge(
                ['_token' => csrf_token()],
                $data
            ))
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('selected_pumps')
                ->count('selected_pumps', 10)
                ->has('selected_pumps.0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        "key",
                        "pumps_count",
                        "name",
                        "pump_id",
                        "article_num",
                        "retail_price",
                        "discounted_price",
                        "retail_price_total",
                        "discounted_price_total",
                        "dn_suction",
                        "dn_pressure",
                        "rated_power",
                        "power_total",
                        "ptp_length",
                        "head",
                        "flow",
                        "main_pumps_count",
                        "fluid_temperature",
                    ])
                )
                ->whereAll([
                    'working_point.x' => $data['flow'],
                    'working_point.y' => $data['head']
                ])
            );
    }

    /**
     * @return void
     * @throws Exception
     * @see PumpFactory dp_standby/peak_performance is the same DCM-G 65-420/A/BAQE/0,25 has
     * @author Max Trunnikov
     * @see EpMakeSelection
     */
    public function test_select_double_pumps(): void
    {
        $this->app->bind(RqMakeSelection::class, RqMakeDoublePumpSelection::class);
        $brand1 = PumpBrand::factory()->create();
        $brand2 = PumpBrand::factory()->create();
        $series1 = PumpSeries::factory()->count(2)->create(['brand_id' => $brand1->id]);
        $series2 = PumpSeries::factory()->count(2)->create(['brand_id' => $brand2->id]);
        foreach ($series1 as $series) {
            Pump::factory()->count(5)->create(['series_id' => $series->id, 'pumpable_type' => Pump::$DOUBLE_PUMP]);
        }
        // will be not selected
        foreach ($series2 as $series) {
            Pump::factory()->count(5)->create([
                'series_id' => $series->id,
                'pumpable_type' => Pump::$SINGLE_PUMP,
            ]);
        }
        $user = User::fakeWithRole();
        $data = [
            'dp_work_scheme_id' => DoublePumpWorkScheme::allOrCached()->random()->id,
            "pump_brand_ids" => [$brand1->id, $brand2->id],
            "fluid_temperature" => 20,
            "power_adjustment_ids" => [2],
            "flow" => 10,
            "head" => 2,
            "range_id" => 3,
            "custom_range" => [0, 100],
            "use_additional_filters" => false,
            "series_ids" => $series1->merge($series2)->pluck('id')->all(),
            "pumpable_type" => Pump::$DOUBLE_PUMP
        ];
        $this->startSession()
            ->from(route('projects.selections.create', "-1"))
            ->actingAs($user)
            ->post(route('selections.select'), array_merge(
                ['_token' => csrf_token()],
                $data
            ))
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('selected_pumps')
                ->count('selected_pumps', 10)
                ->has('selected_pumps.0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        "key",
                        "name",
                        "pump_id",
                        "article_num",
                        "retail_price",
                        "discounted_price",
                        "retail_price_total",
                        "discounted_price_total",
                        "dn_suction",
                        "dn_pressure",
                        "rated_power",
                        "power_total",
                        "ptp_length",
                        "head",
                        "flow",
                        "fluid_temperature",
                        "dp_work_scheme_id"
                    ])
                )
                ->whereAll([
                    'working_point.x' => $data['flow'],
                    'working_point.y' => $data['head']
                ])
            );
    }

    /**
     * @return void
     * @see EpMakeSelection
     * @see PumpFactory sp_performance is the same TOP-S 25/5 EM PN6/10 has
     * @author Max Trunnikov
     */
    public function test_select_single_pumps_with_prices(): void
    {
        $this->app->bind(RqMakeSelection::class, RqMakeSinglePumpSelection::class);
        $brand1 = PumpBrand::factory()->create();
        $brand2 = PumpBrand::factory()->create();
        $series1 = PumpSeries::factory()->count(2)->create(['brand_id' => $brand1->id]);
        $series2 = PumpSeries::factory()->count(2)->create(['brand_id' => $brand2->id]);
        foreach ($series1 as $series) {
            $pumps = Pump::factory()->count(5)->create(['series_id' => $series->id, 'pumpable_type' => Pump::$SINGLE_PUMP]);
            DB::table('pumps_price_lists')->insert($pumps->map(fn($pump) => [
                'pump_id' => $pump->id,
                'currency_id' => 121, // RUB
                'country_id' => 1, // RUS
                'price' => 123
            ])->all());
        }
        // will be not selected
        foreach ($series2 as $series) {
            Pump::factory()->count(5)->create([
                'series_id' => $series->id,
                'pumpable_type' => Pump::$DOUBLE_PUMP,
            ]);
        }
        $user = User::fakeWithRole(); // RUS, RUB
        $user->update(['country_id' => 1, 'currency_id' => 121]);
        $data = [
            "pump_brand_ids" => [$brand1->id, $brand2->id],
            "fluid_temperature" => 20,
            "power_adjustment_ids" => [2],
            "flow" => 3,
            "head" => 4,
            "main_pumps_counts" => [2],
            "reserve_pumps_count" => 1,
            "range_id" => 3,
            "custom_range" => [0, 100],
            "power_limit_checked" => false,
            "power_limit_condition_id" => null,
            "ptp_length_limit_checked" => false,
            "ptp_length_limit_condition_id" => null,
            "dn_suction_limit_checked" => false,
            "dn_suction_limit_condition_id" => null,
            "dn_pressure_limit_checked" => false,
            "dn_pressure_limit_condition_id" => null,
            "use_additional_filters" => true,
            "series_ids" => $series1->merge($series2)->pluck('id')->all(),
            "pumpable_type" => Pump::$SINGLE_PUMP
        ];
        $res = json_decode($this->startSession()
            ->from(route('projects.selections.create', "-1"))
            ->actingAs($user)
            ->post(route('selections.select'), array_merge(
                ['_token' => csrf_token()],
                $data
            ))
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('selected_pumps')
                ->count('selected_pumps', 10)
                ->has('selected_pumps.0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        "key",
                        "pumps_count",
                        "name",
                        "pump_id",
                        "article_num",
                        "retail_price",
                        "discounted_price",
                        "retail_price_total",
                        "discounted_price_total",
                        "dn_suction",
                        "dn_pressure",
                        "rated_power",
                        "power_total",
                        "ptp_length",
                        "head",
                        "flow",
                        "main_pumps_count",
                        "fluid_temperature",
                    ])
                )
                ->whereAll([
                    'working_point.x' => $data['flow'],
                    'working_point.y' => $data['head']
                ])
            )->getContent());
        foreach ($res->selected_pumps as $pump) {
            $this->assertEquals(123, $pump->retail_price);
            $this->assertEquals(123, $pump->discounted_price);
            $this->assertEquals(369, $pump->retail_price_total);
            $this->assertEquals(369, $pump->discounted_price_total);

        }
    }

    /**
     * @return void
     * @see EpExportSelection
     * @author Max Trunnikov
     */
    public function test_export_selection()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection = Selection::fakeForProject($project);
        $this->startSession()
            ->actingAs($user)
            ->post(route('selections.export', $selection->id), [
                'token' => csrf_token(),
                'print_pump_image' => true,
                'print_pump_sizes_image' => true,
                'print_pump_electric_diagram_image' => true,
                'print_pump_cross_sectional_drawing_image' => true,
            ])
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload();
    }

    /**
     * @return void
     * @see EpExportSelectionAtOnce
     * @see RqExportSinglePumpSelectionAtOnce
     * @author Max Trunnikovv
     */
    public function test_export_at_once_single_pump_selection()
    {
        $this->app->bind(RqExportAtOnceSelection::class, RqExportSinglePumpSelectionAtOnce::class);
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->post(route('selections.export.at_once'), [
                '_token' => csrf_token(),
                'print_pump_image' => true,
                'print_pump_sizes_image' => true,
                'print_pump_electric_diagram_image' => true,
                'print_pump_cross_sectional_drawing_image' => true,
                'selected_pump_name' => $this->faker->name(),
                'pump_id' => Pump::factory()->create([
                    'pumpable_type' => Pump::$SINGLE_PUMP
                ])->id,
                'pumps_count' => $this->faker->numberBetween(1, 9),
                'reserve_pumps_count' => $this->faker->numberBetween(0, 4),
                'flow' => $this->faker->randomFloat(2, 0, 500),
                'head' => $this->faker->randomFloat(2, 0, 500),
                'fluid_temperature' => $this->faker->numberBetween(-50, 150)
            ])
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload();
    }

    /**
     * @return void
     * @throws Exception
     * @see RqExportDoublePumpSelectionAtOnce
     * @see EpExportSelectionAtOnce
     * @author Max Trunnikov
     */
    public function test_export_at_once_double_pump_selection()
    {
        $this->app->bind(RqExportAtOnceSelection::class, RqExportDoublePumpSelectionAtOnce::class);
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->post(route('selections.export.at_once'), [
                '_token' => csrf_token(),
                'print_pump_image' => true,
                'print_pump_sizes_image' => true,
                'print_pump_electric_diagram_image' => true,
                'print_pump_cross_sectional_drawing_image' => true,
                'selected_pump_name' => $this->faker->name(),
                'pump_id' => Pump::factory()->create([
                    'pumpable_type' => Pump::$DOUBLE_PUMP
                ])->id,
                'dp_work_scheme_id' => DoublePumpWorkScheme::allOrCached()->random()->id,
                'flow' => $this->faker->randomFloat(2, 0, 500),
                'head' => $this->faker->randomFloat(2, 0, 500),
                'fluid_temperature' => $this->faker->numberBetween(-50, 150)
            ])
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload();
    }

    // TODO: make test for every filter
}
