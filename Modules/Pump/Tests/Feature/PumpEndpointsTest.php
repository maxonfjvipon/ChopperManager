<?php

namespace Modules\Pump\Tests\Feature\Pumps;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpsPriceList;
use Modules\Pump\Http\Endpoints\EpAddPumpToProjects;
use Modules\Pump\Http\Endpoints\EpImportPriceLists;
use Modules\Pump\Http\Endpoints\EpImportPumps;
use Modules\Pump\Http\Endpoints\EpLoadPumps;
use Modules\Pump\Http\Endpoints\EpPumps;
use Modules\Pump\Http\Endpoints\EpShowPump;
use Modules\Pump\Transformers\RcDoubleRcPump;
use Modules\Pump\Transformers\RcSinglePump;
use Modules\PumpSeries\Entities\ElPowerAdjustment;
use Modules\PumpSeries\Entities\PumpApplication;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpCategory;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Entities\PumpSeriesAndApplication;
use Modules\PumpSeries\Entities\PumpSeriesAndType;
use Modules\PumpSeries\Entities\PumpType;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\User;
use Tests\TestCase;
use Throwable;

/**
 * @see  User::booted() - after user created, all {@see PumpSeries} and {@see SelectionType}s are available for him
 */
class PumpEndpointsTest extends TestCase
{
    /**
     * @return void
     *
     * @author Max Trunnikov
     */
    public function testClientCannotGetAccessToImportPumpEndpoints()
    {
        $user = User::fakeWithRole();
        $this->startSession()->actingAs($user);

        $this->post(route('pumps.import'), [
            '_token' => csrf_token(),
            'files' => ['file'],
        ])->assertForbidden();
        $this->post(route('pumps.import.price_lists'), [
            '_token' => csrf_token(),
            'files' => ['file'],
        ])->assertForbidden();
        $this->post(route('pumps.import.media'), [
            '_token' => csrf_token(),
            'files' => ['file'],
            'folder' => 'folder',
        ])->assertForbidden();
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see EpPumps
     */
    public function testPumpsIndexEndpoint()
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
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Pump::Index', false)
                ->has('filter_data', fn (AssertableInertia $page) => $page
                    ->has('brands')
                    ->count('brands', $user->available_brands()->count())
                    ->has('brands.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('series')
                    ->count('series', $user->available_series()->count())
                    ->has('series.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('connection_types')
                    ->count('connection_types', ConnectionType::allOrCached()->count())
                    ->has('connection_types.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('dns')
                    ->count('dns', DN::allOrCached()->count())
                    ->has('dns.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('power_adjustments')
                    ->count('power_adjustments', ElPowerAdjustment::allOrCached()->count())
                    ->has('power_adjustments.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('types')
                    ->count('types', PumpType::allOrCached()->count())
                    ->has('types.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('applications')
                    ->count('applications', PumpApplication::allOrCached()->count())
                    ->has('applications.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('mains_connections')
                    ->count('mains_connections', MainsConnection::allOrCached()->count())
                    ->has('mains_connections.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                )
                ->has('projects')
                ->count('projects', $user->projects()->count())
                ->has('projects.0', fn (AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                    ->etc()
                )
            );
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see EpLoadPumps
     */
    public function testLoadSinglePumps()
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
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id', 'article_num_main', 'article_num_archive', 'brand', 'series',
                        'name', 'weight', 'price', 'currency', 'rated_power', 'rated_current', 'connection_type',
                        'fluid_temp_min', 'fluid_temp_max', 'ptp_length', 'dn_suction', 'dn_pressure',
                        'category', 'power_adjustment', 'mains_connection', 'applications', 'types',
                        'is_discontinued',
                    ])->where('pumpable_type', Pump::$SINGLE_PUMP)
                )
            );
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see EpLoadPumps
     */
    public function testLoadDoublePumps()
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
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id', 'article_num_main', 'article_num_archive', 'brand', 'series',
                        'name', 'weight', 'price', 'currency', 'rated_power', 'rated_current', 'connection_type',
                        'fluid_temp_min', 'fluid_temp_max', 'ptp_length', 'dn_suction', 'dn_pressure',
                        'category', 'power_adjustment', 'mains_connection', 'applications', 'types',
                        'is_discontinued',
                    ])->where('pumpable_type', Pump::$DOUBLE_PUMP)
                )
            );
    }

    /**
     * @return void
     *
     * @throws Exception
     * @throws Throwable
     *
     * @author Max Trunnikov
     *
     * @see EpLoadPumps
     */
    public function testLoadSinglePumpsWithFilter()
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
                'filter' => $name,
            ])
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id', 'article_num_main', 'article_num_archive', 'brand', 'series',
                        'name', 'weight', 'price', 'currency', 'rated_power', 'rated_current', 'connection_type',
                        'fluid_temp_min', 'fluid_temp_max', 'ptp_length', 'dn_suction', 'dn_pressure',
                        'category', 'power_adjustment', 'mains_connection', 'applications', 'types',
                        'is_discontinued',
                    ])->where('pumpable_type', Pump::$SINGLE_PUMP)
                )
            )->getContent());
        foreach ($pumps as $pump) {
            $this->assertEquals($name, $pump->name);
        }
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see  EpShowPump
     * @see  RcSinglePump
     */
    public function testShowSinglePumpWithoutCurves()
    {
        $series = PumpSeries::factory()->create();
        PumpSeriesAndType::createForSeries($series, PumpType::allOrCached()->pluck('id')->all());
        PumpSeriesAndApplication::createForSeries($series, PumpType::allOrCached()->pluck('id')->all());

        $pump = Pump::factory()->create([
            'series_id' => $series->id,
            'pumpable_type' => Pump::$SINGLE_PUMP,
        ]);
        $user = User::fakeWithRole();
        $priceList = PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'country_id' => $user->country_id,
        ]);
        $this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.show', $pump->id), [
                '_token' => csrf_token(),
                'need_curves' => false,
                'pumpable_type' => Pump::$SINGLE_PUMP,
            ])
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->whereAll([
                    'id' => $pump->id,
                    'article_num_main' => $pump->article_num_main,
                    'article_num_archive' => $pump->article_num_archive,
                    'is_discontinued' => __('tooltips.popconfirm.yes'),
                    'full_name' => $pump->full_name,
                    'weight' => $pump->weight,
                    'price' => $priceList->price,
                    'currency' => $priceList->currency->code_name,
                    'rated_power' => $pump->rated_power,
                    'rated_current' => $pump->rated_current,
                    'connection_type' => $pump->connection_type->name,
                    'fluid_temp_min' => $pump->fluid_temp_min,
                    'fluid_temp_max' => $pump->fluid_temp_max,
                    'ptp_length' => $pump->ptp_length,
                    'dn_suction' => $pump->dn_suction->value,
                    'dn_pressure' => $pump->dn_pressure->value,
                    'category' => $pump->series->category->name,
                    'power_adjustment' => $pump->series->power_adjustment->name,
                    'connection' => $pump->mains_connection->full_value,
                    'applications' => $pump->applications,
                    'types' => $pump->types,
                    'description' => $pump->description,
                    'pumpable_type' => Pump::$SINGLE_PUMP,
                ])
                ->has('images', fn (AssertableJson $json) => $json
                    ->hasAll(['pump', 'sizes', 'electric_diagram', 'cross_sectional_drawing'])
                )
                ->has('files')
            );
    }

    /**
     * @return void
     *
     * @todo add additional curves check
     *
     * @see EpShowPump
     * @see RcSinglePump
     *
     * @author Max Trunnikov
     */
    public function testShowSinglePumpWithCurves()
    {
        $pump = Pump::factory()->create();
        $user = User::fakeWithRole();
        $this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.show', $pump->id), [
                '_token' => csrf_token(),
                'need_curves' => true,
                'pumpable_type' => Pump::$SINGLE_PUMP,
            ])
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('main_curves')
                ->etc()
            );
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @todo add additional curves
     *
     * @see RcDoubleRcPump
     * @see EpShowPump
     *
     * @author Max Trunnikov
     */
    public function testShowDoublePumpWithCurves()
    {
        $series = PumpSeries::factory()->create();
        PumpSeriesAndType::createForSeries($series, PumpType::allOrCached()->pluck('id')->all());
        PumpSeriesAndApplication::createForSeries($series, PumpType::allOrCached()->pluck('id')->all());

        $pump = Pump::factory()->create([
            'series_id' => $series->id,
            'pumpable_type' => Pump::$DOUBLE_PUMP,
        ]);
        $user = User::fakeWithRole();
        $priceList = PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'country_id' => $user->country_id,
        ]);
        $this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.show', $pump->id), [
                '_token' => csrf_token(),
                'need_curves' => true,
                'pumpable_type' => Pump::$DOUBLE_PUMP,
            ])
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->whereAll([
                    'id' => $pump->id,
                    'article_num_main' => $pump->article_num_main,
                    'article_num_archive' => $pump->article_num_archive,
                    'is_discontinued' => __('tooltips.popconfirm.yes'),
                    'full_name' => $pump->full_name,
                    'weight' => $pump->weight,
                    'price' => $priceList->price,
                    'currency' => $priceList->currency->code_name,
                    'rated_power' => $pump->rated_power,
                    'rated_current' => $pump->rated_current,
                    'connection_type' => $pump->connection_type->name,
                    'fluid_temp_min' => $pump->fluid_temp_min,
                    'fluid_temp_max' => $pump->fluid_temp_max,
                    'ptp_length' => $pump->ptp_length,
                    'dn_suction' => $pump->dn_suction->value,
                    'dn_pressure' => $pump->dn_pressure->value,
                    'category' => $pump->series->category->name,
                    'power_adjustment' => $pump->series->power_adjustment->name,
                    'connection' => $pump->mains_connection->full_value,
                    'applications' => $pump->applications,
                    'types' => $pump->types,
                    'description' => $pump->description,
                    'pumpable_type' => Pump::$DOUBLE_PUMP,
                ])
                ->has('images', fn (AssertableJson $json) => $json
                    ->hasAll(['pump', 'sizes', 'electric_diagram', 'cross_sectional_drawing'])
                )
                ->has('files')
                ->has('main_curves')
            );
    }

    /**
     * @return void
     *
     * @see EpAddPumpToProjects
     *
     * @author Max Trunnikov
     */
    public function testAddPumpToProjectsEndpoint()
    {
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$SINGLE_PUMP]);
        $user = User::fakeWithRole();
        $project1 = Project::fakeForUser($user);
        $project2 = Project::fakeForUser($user);

        $this->startSession()
            ->actingAs($user)
            ->from(route('pumps.index'))
            ->post(route('pumps.add_to_projects', $pump->id), [
                '_token' => csrf_token(),
                'project_ids' => [$project1->id, $project2->id],
                'pumps_count' => 2,
            ])
            ->assertStatus(200);
        $this->assertDatabaseHas('selections', [
            'project_id' => $project1->id,
            'pumps_count' => 2,
            'main_pumps_counts' => 2,
        ]);
        $this->assertDatabaseHas('selections', [
            'project_id' => $project2->id,
            'pumps_count' => 2,
            'main_pumps_counts' => 2,
        ]);
    }

    /**
     * @return void
     *
     * @see EpImportPumps
     *
     * @author Max Trunnikov
     */
    public function testPumpsImportEndpoint()
    {
        $wilo = PumpBrand::factory()->create(['name' => 'Wilo']);
        $dab = PumpBrand::factory()->create(['name' => 'DAB']);
        $mhi = PumpSeries::factory()->create(['name' => 'MHI', 'brand_id' => $wilo->id, 'category_id' => PumpCategory::$SINGLE_PUMP]);
        $top_sd = PumpSeries::factory()->create(['name' => 'TOP-SD', 'brand_id' => $wilo->id, 'category_id' => PumpCategory::$DOUBLE_PUMP]);
        $d = PumpSeries::factory()->create(['name' => 'D', 'brand_id' => $dab->id, 'category_id' => PumpCategory::$DOUBLE_PUMP]);
        $this->startSession()
            ->from(route('pump_series.index'))
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pumps.import'), [
                '_token' => csrf_token(),
                'files' => [
                    new UploadedFile(__DIR__.'/DAB.xlsx', 'DAB.xlsx'),
                    new UploadedFile(__DIR__.'/Wilo.xlsx', 'Wilo.xlsx'),
                ],
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pumps.index'));
        $this->assertDatabaseCount('pumps', 68); // check amount of pumps in uploaded files
        $this->assertDatabaseHas('pumps', [
            'article_num_main' => '4210722',
            'article_num_archive' => '4148926',
            'series_id' => $mhi->id,
            'name' => 'MHI 206-1/E/3-400-50-2/IE3',
            'weight' => 13.8,
            'rated_power' => 1.1,
            'rated_current' => 2.8,
            'connection_type_id' => 1,
            'dn_suction_id' => 3,
            'dn_pressure_id' => 3,
            'fluid_temp_min' => -30,
            'fluid_temp_max' => 110,
            'ptp_length' => 0,
            'connection_id' => 3,
            'sp_performance' => '0 69.49 0.7708 66.14 1.497 62.24 2.238 56.71 2.911 50.33 3.682 41.66 4.423 31.93 4.999 23.18 5.455 15.34 5.979 5.138',
            'is_discontinued' => false,
            'description' => '',
            'pumpable_type' => Pump::$SINGLE_PUMP,
            'dp_standby_performance' => null,
            'dp_peak_performance' => null,
            'image' => '',
            'sizes_image' => '',
            'electric_diagram_image' => '',
            'cross_sectional_drawing_image' => '',
        ]);
        $this->assertDatabaseHas('pumps', [
            'article_num_main' => '505822041',
            'article_num_archive' => '',
            'series_id' => $d->id,
            'name' => 'D 50/250.40 M',
            'weight' => 14.2,
            'rated_power' => 0.1462,
            'rated_current' => 0.95,
            'connection_type_id' => 2,
            'dn_suction_id' => 5,
            'dn_pressure_id' => 5,
            'fluid_temp_min' => -10,
            'fluid_temp_max' => 110,
            'ptp_length' => 250,
            'connection_id' => 1,
            'sp_performance' => null,
            'is_discontinued' => 1,
            'description' => '',
            'pumpable_type' => Pump::$DOUBLE_PUMP,
            'dp_standby_performance' => '0.000 5.777 0.884 5.600 2.489 5.096 3.551 4.600 4.440 4.109 5.230 3.609 5.971 3.118 6.687 2.618 7.379 2.134 8.070 1.641 8.787 1.153 9.528 0.657 9.874 0.436',
            'dp_peak_performance' => '0.015 5.766 1.223 5.666 3.542 5.381 5.770 4.939 7.726 4.451 9.385 3.972 10.909 3.486 12.364 2.998 13.751 2.515 15.115 2.030 16.502 1.539 17.911 1.052 19.277 0.588 19.753 0.436',
            'image' => '',
            'sizes_image' => '',
            'electric_diagram_image' => '',
            'cross_sectional_drawing_image' => '',
        ]);
    }

    /**
     * @return void
     *
     * @see EpImportPumps
     *
     * @author Max Trunnikov
     */
    public function testImportPumpsWithInvalidData()
    {
        $wilo = PumpBrand::factory()->create(['name' => 'Wilo']);
        PumpSeries::factory()->createMany([
            ['name' => 'MHI', 'brand_id' => $wilo->id, 'category_id' => PumpCategory::$SINGLE_PUMP],
            ['name' => 'TOP-SD', 'brand_id' => $wilo->id, 'category_id' => PumpCategory::$DOUBLE_PUMP],
        ]);
        $this->startSession()
            ->from(route('pump_series.index'))
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pumps.import'), [
                '_token' => csrf_token(),
                'files' => [
                    new UploadedFile(__DIR__.'/Wilo invalid.xlsx', 'Wilo invalid.xlsx'),
                ],
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index')) // redirected back, not to {@code route('pumps.index')}
            ->assertSessionHas('errorBag', [
                [
                    'head' => [
                        'key' => __('validation.attributes.import.pumps.article_num_main'),
                        'value' => 'Unknown',
                    ],
                    'file' => '',
                    'message' => __('validation.required', [
                        'attribute' => __('validation.attributes.import.pumps.article_num_main'),
                    ]),
                ], [
                    'head' => [
                        'key' => __('validation.attributes.import.pumps.article_num_main'),
                        'value' => '2044015',
                    ],
                    'file' => '',
                    'message' => __('validation.import.in_array', [
                        'attribute' => __('validation.attributes.import.pumps.series'),
                    ]),
                ],
            ]);
        $this->assertDatabaseCount('pumps', 0);
    }

    /**
     * @return void
     *
     * @see EpImportPriceLists
     *
     * @author Max Trunnikov
     */
    public function testImportPumpsPriceLists()
    {
        $tops = Pump::factory()->createMany([
            ['article_num_main' => '2044009'],
            ['article_num_main' => '2044010'],
            ['article_num_main' => '2048320'],
            ['article_num_main' => '2048321'],
            ['article_num_main' => '2061962'],
        ]);
        $mhi = Pump::factory()->createMany([
            ['article_num_main' => '4024282'],
            ['article_num_main' => '4024283'],
            ['article_num_main' => '4024284'],
        ]);
        $this->startSession()
            ->from(route('pumps.index'))
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pumps.import.price_lists'), [
                '_token' => csrf_token(),
                'files' => [new UploadedFile(__DIR__.'/WiloPriceLists.xlsx', 'WiloPriceLists.xlsx')],
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pumps.index'));
        $this->assertDatabaseCount('pumps_price_lists', 8);
        $tops->map(function ($pump) {
            $this->assertDatabaseHas('pumps_price_lists', [
                'pump_id' => $pump->id,
                'country_id' => 3, // BLR
                'currency_id' => 1, // EUR
            ]);
        });
        $mhi->map(function ($pump) {
            $this->assertDatabaseHas('pumps_price_lists', [
                'pump_id' => $pump->id,
                'country_id' => 1, // RUS
                'currency_id' => 121, // RUB
            ]);
        });
    }

    /**
     * @return void
     *
     * @see EpImportPumps
     *
     * @author Max Trunnikov
     */
    public function testImportPumpsPriceListsWithInvalidData()
    {
        Pump::factory()->createMany([
            ['article_num_main' => '2044009'], // will be not found
            ['article_num_main' => '2044010'],
            ['article_num_main' => '2048320'],
            ['article_num_main' => '2048321'],
            ['article_num_main' => '2061962'],
            ['article_num_main' => '4024282'],
            ['article_num_main' => '4024283'],
            ['article_num_main' => '4024284'],
        ]);
        $this->startSession()
            ->from(route('pumps.index'))
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pumps.import.price_lists'), [
                '_token' => csrf_token(),
                'files' => [new UploadedFile(__DIR__.'/WiloPriceListsInvalid.xlsx', 'WiloPriceListsInvalid.xlsx')],
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pumps.index'))
            ->assertSessionHas('errorBag', [
                [
                    'head' => [
                        'key' => __('validation.attributes.import.price_lists.article_num_main'),
                        'value' => '20440091',
                    ],
                    'file' => '',
                    'message' => __('validation.import.in_array', [
                        'attribute' => __('validation.attributes.import.price_lists.article_num_main'),
                    ]),
                ], [
                    'head' => [
                        'key' => __('validation.attributes.import.price_lists.article_num_main'),
                        'value' => '2044010',
                    ],
                    'file' => '',
                    'message' => __('validation.import.in_array', [
                        'attribute' => __('validation.attributes.import.price_lists.country'),
                    ]),
                ], [
                    'head' => [
                        'key' => __('validation.attributes.import.price_lists.article_num_main'),
                        'value' => '2048320',
                    ],
                    'file' => '',
                    'message' => __('validation.import.in_array', [
                        'attribute' => __('validation.attributes.import.price_lists.currency'),
                    ]),
                ], [
                    'head' => [
                        'key' => __('validation.attributes.import.price_lists.article_num_main'),
                        'value' => '2048321',
                    ],
                    'file' => '',
                    'message' => __('validation.required', [
                        'attribute' => __('validation.attributes.import.price_lists.price'),
                    ]),
                ], [
                    'head' => [
                        'key' => __('validation.attributes.import.price_lists.article_num_main'),
                        'value' => 'Unknown',
                    ],
                    'file' => '',
                    'message' => __('validation.required', [
                        'attribute' => __('validation.attributes.import.price_lists.article_num_main'),
                    ]),
                ],
            ]);
        $this->assertDatabaseCount('pumps_price_lists', 0);
    }

    /**
     * @return void
     *
     * @see EpImportPumps
     *
     * @author Max Trunnikov
     */
    public function testImportPumpsMedia()
    {
        $fileName = 'fake-pump-image.jpg';
        $this->startSession()
            ->from(route('pumps.index'))
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pumps.import.media'), [
                '_token' => csrf_token(),
                'files' => [UploadedFile::fake()->image($fileName)],
                'folder' => 'pumps/images',
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pumps.index'))
            ->assertSessionHas('success', 'Media were imported successfully to directory: pumps/images');
    }
}
