<?php

namespace Modules\Pump\Tests\Feature\Pumps;

use Exception;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpSeriesAndApplication;
use Modules\Pump\Entities\PumpSeriesAndType;
use Modules\Pump\Entities\PumpType;
use Modules\Pump\Http\Endpoints\Pump\LoadPumpsEndpoint;
use Modules\Pump\Http\Endpoints\Pump\PumpsIndexEndpoint;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\User;
use Tests\TestCase;
use Throwable;

class PumpEndpointsTest extends TestCase
{
    /**
     * @return void
     */
    public function test_client_cannot_get_access_to_import_pump_endpoints()
    {
        $user = User::fakeWithRole();
        $this->startSession()->actingAs($user);

        $this->post(route('pumps.import'), [
            '_token' => csrf_token(),
            'files' => ['file']
        ])->assertForbidden();
        $this->post(route('pumps.import.price_lists'), [
            '_token' => csrf_token(),
            'files' => ['file']
        ])->assertForbidden();
        $this->post(route('pumps.import.media'), [
            '_token' => csrf_token(),
            'files' => ['file'],
            'folder' => 'folder'
        ])->assertForbidden();
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see PumpsIndexEndpoint
     * @see User::booted() - after user created, all {@see PumpSeries} and {@see SelectionType}s are available for him
     */
    public function test_pumps_index_endpoint()
    {
        $series = PumpSeries::factory()->count(3)->create();
        foreach ($series as $_series) {
            PumpSeriesAndType::createForSeries($_series, PumpType::allOrCached()->pluck('id')->all());
            PumpSeriesAndApplication::createForSeries($_series, PumpType::allOrCached()->pluck('id')->all());
        }

        $user = User::fakeWithRole();
        Project::fakeForUser($user, 3);

        $this->actingAs($user)
            ->get(route('pumps.index'))
            ->assertStatus(200)
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Pump::Pumps/Index', false)
                ->has('filter_data', fn(AssertableInertia $page) => $page
                    ->has('brands')
                    ->count('brands', $user->available_brands()->count())
                    ->has('brands.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('series')
                    ->count('series', $user->available_series()->count())
                    ->has('series.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('connection_types')
                    ->count('connection_types', ConnectionType::allOrCached()->count())
                    ->has('connection_types.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('dns')
                    ->count('dns', DN::allOrCached()->count())
                    ->has('dns.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('power_adjustments')
                    ->count('power_adjustments', ElPowerAdjustment::allOrCached()->count())
                    ->has('power_adjustments.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('types')
                    ->count('types', PumpType::allOrCached()->count())
                    ->has('types.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('applications')
                    ->count('applications', PumpApplication::allOrCached()->count())
                    ->has('applications.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('mains_connections')
                    ->count('mains_connections', MainsConnection::allOrCached()->count())
                    ->has('mains_connections.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                )
                ->has('projects')
                ->count('projects', $user->projects()->count())
                ->has('projects.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                    ->etc()
                )
            );
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see LoadPumpsEndpoint
     * @see User::booted() - after user created, all {@see PumpSeries} and {@see SelectionType}s are available for him
     */
    public function test_load_single_pumps()
    {
        Pump::clearCache();

        $series = PumpSeries::factory()->count(10)->create();
        $user = User::fakeWithRole();
        foreach ($series as $_series) {
            Pump::factory()->count(5)->create(['series_id' => $_series->id]);
        }

        $this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.load'), [
                '_token',
                'pumpable_type' => Pump::$SINGLE_PUMP,
            ])
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        'id', 'article_num_main', 'article_num_archive', 'brand', 'series',
                        'name', 'weight', 'price', 'currency', 'rated_power', 'rated_current', 'connection_type',
                        'fluid_temp_min', 'fluid_temp_max', 'ptp_length', 'dn_suction', 'dn_pressure',
                        'category', 'power_adjustment', 'mains_connection', 'applications', 'types',
                        'is_discontinued'
                    ])->where('pumpable_type', Pump::$SINGLE_PUMP)
                )
            );
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see LoadPumpsEndpoint
     * @see User::booted() - after user created, all {@see PumpSeries} and {@see SelectionType}s are available for him
     */
    public function test_load_double_pumps()
    {
        Pump::clearCache();

        $series = PumpSeries::factory()->count(10)->create();
        $user = User::fakeWithRole();
        foreach ($series as $_series) {
            Pump::factory()->count(5)->create(['series_id' => $_series->id]);
        }

        $this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.load'), [
                '_token',
                'pumpable_type' => Pump::$DOUBLE_PUMP,
            ])
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        'id', 'article_num_main', 'article_num_archive', 'brand', 'series',
                        'name', 'weight', 'price', 'currency', 'rated_power', 'rated_current', 'connection_type',
                        'fluid_temp_min', 'fluid_temp_max', 'ptp_length', 'dn_suction', 'dn_pressure',
                        'category', 'power_adjustment', 'mains_connection', 'applications', 'types',
                        'is_discontinued'
                    ])->where('pumpable_type', Pump::$DOUBLE_PUMP)
                )
            );
    }

    /**
     * @return void
     * @throws Exception
     * @throws Throwable
     * @author Max Trunnikov
     * @see LoadPumpsEndpoint
     * @see User::booted() - after user created, all {@see PumpSeries} and {@see SelectionType}s are available for him
     */
    public function test_load_single_pumps_with_filter_by_name()
    {
        Pump::clearCache();

        $name = 'test';
        $series = PumpSeries::factory()->count(5)->create();
        $user = User::fakeWithRole();
        foreach ($series as $_series) {
            Pump::factory()->count(3)->create(['series_id' => $_series->id]);
            Pump::factory()->count(2)->create(['series_id' => $_series->id, 'name' => $name]);
        }

        $pumps = json_decode($this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.load'), [
                '_token',
                'pumpable_type' => Pump::$SINGLE_PUMP,
                'filter' => $name
            ])
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        'id', 'article_num_main', 'article_num_archive', 'brand', 'series',
                        'name', 'weight', 'price', 'currency', 'rated_power', 'rated_current', 'connection_type',
                        'fluid_temp_min', 'fluid_temp_max', 'ptp_length', 'dn_suction', 'dn_pressure',
                        'category', 'power_adjustment', 'mains_connection', 'applications', 'types',
                        'is_discontinued'
                    ])->where('pumpable_type', Pump::$SINGLE_PUMP)
                )
            )->getContent());
        foreach ($pumps as $pump) {
            $this->assertEquals($name, $pump->name);
        }
    }
}
