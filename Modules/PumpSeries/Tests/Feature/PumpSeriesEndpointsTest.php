<?php

namespace Modules\Pump\Tests\Feature\PumpSeries;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Modules\Pump\Entities\Pump;
use Modules\PumpSeries\Entities\ElPowerAdjustment;
use Modules\PumpSeries\Entities\PumpApplication;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Entities\PumpCategory;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\PumpSeries\Entities\PumpSeriesAndApplication;
use Modules\PumpSeries\Entities\PumpSeriesAndType;
use Modules\PumpSeries\Entities\PumpType;
use Modules\PumpSeries\Http\Endpoints\EpCreatePumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpDestroyPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpEditPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpImportPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpImportPumpSeriesMedia;
use Modules\PumpSeries\Http\Endpoints\EpPumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpRestorePumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpStorePumpSeries;
use Modules\PumpSeries\Http\Endpoints\EpUpdatePumpSeries;
use Modules\PumpSeries\Http\Requests\RqStorePumpSeries;
use Modules\User\Entities\User;
use Tests\TestCase;

class PumpSeriesEndpointsTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     *
     * @author Max Trunnikov
     */
    public function testUnauthorizedUserCannotGetAccessToSeriesPages()
    {
        $this->get(route('pump_series.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->get(route('pump_series.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $series = PumpSeries::factory()->create();
        $this->get(route('pump_series.edit', $series->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->get(route('pump_series.restore', $series->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * @return void
     *
     * @author Max Trunnikov
     */
    public function testClientCannotGetAccessToSeriesPage()
    {
        $user = User::fakeWithRole();
        $this->actingAs($user)
            ->get(route('pump_series.index'))
            ->assertForbidden();
        $this->actingAs($user)
            ->get(route('pump_series.create'))
            ->assertForbidden();
        $series = PumpSeries::factory()->create();
        $this->actingAs($user)
            ->get(route('pump_series.edit', $series->id))
            ->assertForbidden();
        $this->actingAs($user)
            ->get(route('pump_series.restore', $series->id))
            ->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpPumpSeries
     *
     * @author Max Trunnikov
     */
    public function testPumpSeriesIndexEndpoint()
    {
        $user = User::fakeWithRole('SuperAdmin');
        $brands = PumpBrand::factory()->count(2)->create();
        foreach ($brands as $brand) {
            PumpSeries::factory()->count(2)->create([
                'brand_id' => $brand,
            ]);
        }
        $this->actingAs($user)
            ->get(route('pump_series.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('PumpSeries::Index', false)
                ->has('filter_data', fn (AssertableInertia $page) => $page
                    ->has('brands')
                    ->has('brands.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('categories')
                    ->has('categories.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('power_adjustments')
                    ->has('power_adjustments.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('applications')
                    ->has('applications.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('types')
                    ->has('types.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                )
                ->has('brands')
                ->count('brands', PumpBrand::count())
                ->has('series')
                ->count('series', PumpSeries::count())
                ->has('series.0', fn (AssertableInertia $page) => $page
                    ->has('id')
                    ->has('brand')
                    ->has('name')
                    ->has('category')
                    ->has('power_adjustment')
                    ->has('applications')
                    ->has('types')
                    ->has('is_discontinued')
                )
            )->assertStatus(200);
    }

    /**
     * @return void
     *
     * @see EpCreatePumpSeries
     *
     * @author Max Trunnikov
     */
    public function testPumpSeriesCreateEndpoint()
    {
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('pump_series.create'))
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('PumpSeries::Create', false)
                ->has('pump_series_props', fn (AssertableInertia $page) => $page
                    ->has('data', fn (AssertableInertia $page) => $page
                        ->has('brands')
                        ->has('categories')
                        ->has('power_adjustments')
                        ->has('applications')
                        ->has('types')
                    )
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
     * @see EpEditPumpSeries
     */
    public function testEditPumpSeriesEndpoint()
    {
        $series = PumpSeries::factory()->create();
        PumpSeriesAndType::createForSeries($series, PumpType::all()->pluck('id')->all());
        PumpSeriesAndApplication::createForSeries($series, PumpApplication::all()->pluck('id')->all());
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('pump_series.edit', $series->id))
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('PumpSeries::Edit', false)
                ->has('pump_series_props', fn (AssertableInertia $page) => $page
                    ->has('data', fn (AssertableInertia $page) => $page
                        ->has('brands')
                        ->has('categories')
                        ->has('power_adjustments')
                        ->has('applications')
                        ->has('types')
                    )
                )
                ->has('series', fn (AssertableInertia $page) => $page
                    ->has('data', fn (AssertableInertia $page) => $page
                        ->where('id', $series->id)
                        ->where('brand_id', $series->brand->id)
                        ->where('name', $series->name)
                        ->where('power_adjustment_id', $series->power_adjustment_id)
                        ->where('category_id', $series->category_id)
                        ->where('types', $series->types()->pluck('pump_types.id')->all())
                        ->where('applications', $series->applications()->pluck('pump_applications.id')->all())
                        ->where('is_discontinued', $series->is_discontinued)
                        ->where('image', $series->image)
                        ->has('picture')
                    )
                )
            );
    }

    /**
     * @return void
     *
     * @see EpRestorePumpSeries
     *
     * @author Max Trunnikov
     */
    public function testPumpSeriesRestoreEndpoint()
    {
        $series = PumpSeries::factory()->create();
        $series->delete();
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->from(route('pump_series.index'))
            ->get(route('pump_series.restore', $series->id))
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertNotSoftDeleted($series);
    }

    /**
     * @return void
     *
     * @see EpDestroyPumpSeries
     *
     * @author Max Trunnikov
     */
    public function testClientCannotDestroyPumpSeries()
    {
        $series = PumpSeries::factory()->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->delete(route('pump_series.destroy', $series->id), [
                '_token' => csrf_token(),
            ])
            ->assertForbidden();
        $this->assertNotSoftDeleted($series);
    }

    /**
     * @return void
     *
     * @see EpDestroyPumpSeries
     *
     * @author Max Trunnikov
     */
    public function testPumpSeriesDestroyEndpoint()
    {
        $series = PumpSeries::factory()->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->from(route('pump_series.index'))
            ->delete(route('pump_series.destroy', $series->id), [
                '_token' => csrf_token(),
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertSoftDeleted($series);
    }

    /**
     * @throws Exception
     *
     * @see EpStorePumpSeries
     * @see RqStorePumpSeries
     *
     * @author Max Trunnikov
     */
    public function testClientCannotStoreEndpoint()
    {
        $brand = PumpBrand::factory()->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->from(route('pump_series.create'))
            ->post(route('pump_series.store'), [
                '_token' => csrf_token(),
                'brand_id' => $brand->id,
                'name' => $this->faker->unique()->name(),
                'power_adjustment_id' => ElPowerAdjustment::all()->random()->id,
                'category_id' => PumpCategory::all()->random()->id,
                'types' => [],
                'applications' => [],
            ])->assertForbidden();
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see EpStorePumpSeries
     */
    public function testPumpSeriesStoreEndpoint()
    {
        $brand = PumpBrand::factory()->create();
        $seriesData = [
            'brand_id' => $brand->id,
            'name' => $this->faker->unique()->name(),
            'power_adjustment_id' => ElPowerAdjustment::all()->random()->id,
            'category_id' => PumpCategory::all()->random()->id,
        ];
        $typesAndApplications = [
            'types' => PumpType::all()->pluck('id')->all(),
            'applications' => PumpApplication::allOrCached()->pluck('id')->all(),
        ];
        $user = (User::fakeWithRole('SuperAdmin'));
        $this->startSession()
            ->actingAs($user)
            ->from(route('pump_series.create'))
            ->post(route('pump_series.store'), array_merge(
                ['_token' => csrf_token()],
                $seriesData,
                $typesAndApplications
            ))
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertDatabaseHas('pump_series', $seriesData);
        $this->assertDatabaseCount('users_and_pump_series', 1);
    }

    /**
     * @throws Exception
     *
     * @see EpStorePumpSeries
     * @see RqStorePumpSeries
     *
     * @author Max Trunnikov
     */
    public function testClientCannotUpdatePumpSeries()
    {
        $series = PumpSeries::factory()->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->put(route('pump_series.update', $series->id), [
                '_token' => csrf_token(),
                'brand_id' => $series->brand_id,
                'name' => $this->faker->unique()->name(),
                'power_adjustment_id' => ElPowerAdjustment::allOrCached()->random()->id,
                'category_id' => PumpCategory::allOrCached()->random()->id,
                'is_discontinued' => false,
            ])
            ->assertForbidden();
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see EpUpdatePumpSeries
     */
    public function testUpdatePumpSeriesEndpoint()
    {
        $series = PumpSeries::factory()->create();
        $seriesToUpdate = [
            'brand_id' => PumpBrand::factory()->create()->id,
            'name' => $this->faker->unique()->name(),
            'power_adjustment_id' => ElPowerAdjustment::allOrCached()->random()->id,
            'category_id' => PumpCategory::allOrCached()->random()->id,
            'is_discontinued' => false,
        ];
        $extra = [
            'applications' => PumpApplication::allOrCached()->pluck('id')->all(),
            'types' => PumpType::allOrCached()->pluck('id')->all(),
        ];
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->put(route('pump_series.update', $series->id), array_merge(
                ['_token' => csrf_token()],
                $seriesToUpdate,
                $extra
            ))
            ->assertRedirect(route('pump_series.index'))
            ->assertStatus(302);
        $this->assertDatabaseHas('pump_series', array_merge([
            'id' => $series->id, ],
            $seriesToUpdate
        ));
        $this->assertDatabaseCount('pump_series_and_types', PumpType::allOrCached()->count());
        $this->assertDatabaseCount('pump_series_and_applications', PumpApplication::allOrCached()->count());
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @author Max Trunnikov
     *
     * @see EpUpdatePumpSeries
     */
    public function testDiscontinuingPumpSeriesDiscontinuesPumpsOfTheSeries()
    {
        $series = PumpSeries::factory()->create();
        Pump::factory()->count(10)->create(['series_id' => $series->id]);
        Pump::allOrCached(); // cache pumps
        $this->assertFalse(Pump::all()->random()->is_discontinued_with_series);
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->put(route('pump_series.update', $series->id), [
                '_token' => csrf_token(),
                'brand_id' => $series->brand_id,
                'name' => $this->faker->unique()->name(),
                'power_adjustment_id' => ElPowerAdjustment::allOrCached()->random()->id,
                'category_id' => PumpCategory::allOrCached()->random()->id,
                'is_discontinued' => true,
            ]);
        $this->assertFalse(Pump::all()->random()->is_discontinued);
        $this->assertTrue(Pump::all()->random()->is_discontinued_with_series);
        $this->assertFalse(Cache::has('pumps'));
    }

    /**
     * @return void
     *
     * @see EpImportPumpSeriesMedia
     *
     * @author Max Trunnikov
     */
    public function testClientCannotImportPumpSeriesMedia()
    {
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->post(route('pump_series.import.media'), [
                '_token' => csrf_token(),
                'files' => ['file'],
                'folder' => 'folder',
            ])->assertForbidden();
    }

    public function testPumpSeriesPumpSeriesImportMedia()
    {
        $fileName = 'fake-pump-series.jpg';
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pump_series.import.media'), [
                '_token' => csrf_token(),
                'files' => [UploadedFile::fake()->image($fileName)],
                'folder' => 'pump_series/images',
            ]);
        $this->assertTrue(Storage::disk('media')
            ->exists('1/pump_series/images/'.$fileName));
    }

    /**
     * @return void
     *
     * @see EpImportPumpSeries
     *
     * @author Max Trunnikov
     */
    public function testClientCannotImportPumpSeries()
    {
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->post(route('pump_series.import'), [
                '_token' => csrf_token(),
                'files' => ['file'],
            ])->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpImportPumpSeries
     *
     * @author Max Trunnikov
     */
    public function testPumpSeriesImport()
    {
        PumpBrand::factory()->create(['name' => 'Wilo']);
        PumpBrand::factory()->create(['name' => 'DAB']);
        $file = new UploadedFile(__DIR__.'/Series.xlsx', 'Series.xlsx');
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->from(route('pump_series.index'))
            ->post(route('pump_series.import'), [
                '_token' => csrf_token(),
                'files' => [$file],
            ])->assertRedirect(route('pump_series.index'));
        $this->assertDatabaseHas('pump_series', [
            'name' => 'TOP-S',
            'category_id' => 1,
            'power_adjustment_id' => 2,
            'is_discontinued' => false,
            'image' => 'pump_series/images/TOP-S.jpg',
            'temps_min' => null,
            'temps_max' => null,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('pump_series', [
            'name' => 'BMH, BPH',
            'category_id' => 2,
            'power_adjustment_id' => 1,
            'is_discontinued' => true,
            'image' => 'pump_series/images/BMH, BPH.jpg',
            'temps_min' => null,
            'temps_max' => null,
            'deleted_at' => null,
        ]);
        $top_s = PumpSeries::firstWhere('name', 'TOP-S');
        $bmh = PumpSeries::firstWhere('name', 'BMH, BPH');
        $this->assertEquals(5, $top_s->applications()->count());
        $this->assertEquals(2, $top_s->types()->count());
        $this->assertEquals(2, $bmh->applications()->count());
        $this->assertEquals(1, $bmh->types()->count());
    }

    /**
     * Import pump series when brands don't exist.
     *
     * @return void
     *
     * @see EpImportPumpSeries
     *
     * @author Max Trunnikov
     */
    public function testPumpSeriesImportWithInvalidData()
    {
        $file = new UploadedFile(__DIR__.'/Series.xlsx', 'Series.xlsx');
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->from(route('pump_series.index'))
            ->post(route('pump_series.import'), [
                '_token' => csrf_token(),
                'files' => [$file],
            ])
            ->assertRedirect(route('pump_series.index'))
            ->assertStatus(302)
            ->assertSessionHas('errorBag', [
                [
                    'head' => [
                        'key' => __('validation.attributes.import.pump_series.name'),
                        'value' => 'Wilo TOP-S',
                    ],
                    'file' => '',
                    'message' => __('validation.import.in_array', [
                        'attribute' => __('validation.attributes.import.pump_series.brand'),
                    ]),
                ], [
                    'head' => [
                        'key' => __('validation.attributes.import.pump_series.name'),
                        'value' => 'DAB BMH, BPH',
                    ],
                    'file' => '',
                    'message' => __('validation.import.in_array', [
                        'attribute' => __('validation.attributes.import.pump_series.brand'),
                    ]),
                ],
            ]);
        $this->assertDatabaseMissing('pump_series', [
            'name' => 'TOP-S',
            'category_id' => 1,
            'power_adjustment_id' => 2,
            'is_discontinued' => false,
            'image' => 'pump_series/images/TOP-S.jpg',
            'temps_min' => null,
            'temps_max' => null,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseMissing('pump_series', [
            'name' => 'BMH, BPH',
            'category_id' => 2,
            'power_adjustment_id' => 1,
            'is_discontinued' => true,
            'image' => 'pump_series/images/BMH, BPH.jpg',
            'temps_min' => null,
            'temps_max' => null,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseCount('pump_series_and_applications', 0);
        $this->assertDatabaseCount('pump_series_and_types', 0);
    }
}
