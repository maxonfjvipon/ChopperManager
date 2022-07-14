<?php

namespace Modules\PumpSeries\Tests\Feature;

use function csrf_token;
use Inertia\Testing\AssertableInertia;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpCreatePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpDestroyPumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpEditPumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpRestorePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpStorePumpBrand;
use Modules\PumpSeries\Http\Endpoints\EpUpdatePumpBrand;
use Modules\User\Entities\User;
use function route;
use Tests\TestCase;

class PumpBrandEndpointsTest extends TestCase
{
    /**
     * @return void
     *
     * @see EpStorePumpBrand
     * @see EpCreatePumpBrand
     * @see EpEditPumpBrand
     * @see EpDestroyPumpBrand
     * @see EpRestorePumpBrand
     * @see EpUpdatePumpBrand
     *
     * @author Max Trunnikov
     */
    public function testClientCannotGetAccessToBrandsPages()
    {
        $user = User::fakeWithRole();
        $brand = PumpBrand::factory()->create();

        $this->actingAs($user);
        $this->get(route('pump_brands.create'))->assertForbidden();
        $this->get(route('pump_brands.edit', $brand))->assertForbidden();
        $this->get(route('pump_brands.restore', $brand))->assertForbidden();
        $this->startSession()
            ->put(route('pump_brands.update', $brand->id), [
                '_token' => csrf_token(),
                'name' => 'Test name',
            ])->assertForbidden();
        $this->delete(route('pump_brands.destroy', $brand->id), [
            '_token' => csrf_token(),
        ])->assertForbidden();
        $this->post(route('pump_brands.store'), [
            '_token' => csrf_token(),
            'name' => 'Test',
        ])->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpCreatePumpBrand
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsCreateEndpoint()
    {
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('pump_brands.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('PumpSeries::PumpBrands/Create', false)
            );
    }

    /**
     * @return void
     *
     * @see EpStorePumpBrand
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsStoreEndpoint()
    {
        $name = 'Test brand';
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('pump_brands.store'), [
                '_token' => csrf_token(),
                'name' => $name,
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertDatabaseHas('pump_brands', [
            'name' => $name,
        ]);
    }

    /**
     * @return void
     *
     * @see EpEditPumpBrand
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsEditEndpoint()
    {
        $brand = PumpBrand::factory()->create();
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('pump_brands.edit', $brand->id))
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('PumpSeries::PumpBrands/Edit', false)
                ->has('brand', fn (AssertableInertia $page) => $page
                    ->has('data', fn (AssertableInertia $page) => $page
                        ->where('id', $brand->id)
                        ->where('name', $brand->name)
                    )
                )
            );
    }

    /**
     * @return void
     *
     * @see EpRestorePumpBrand
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsRestoreEndpoint()
    {
        $brand = PumpBrand::factory()->create();
        $brand->delete();
        $this->assertSoftDeleted($brand);
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('pump_brands.restore', $brand->id))
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertNotSoftDeleted($brand);
    }

    /**
     * @return void
     *
     * @see EpUpdatePumpBrand
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsUpdateEndpoint()
    {
        $name = 'new name';
        $brand = PumpBrand::factory()->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->put(route('pump_brands.update', $brand->id), [
                '_token' => csrf_token(),
                'name' => $name,
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertDatabaseHas('pump_brands', [
            'id' => $brand->id,
            'name' => $name,
        ]);
    }

    /**
     * @return void
     *
     * @see EpDestroyPumpBrand
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsDestroyEndpoint()
    {
        $brand = PumpBrand::factory()->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->delete(route('pump_brands.destroy', $brand->id), [
                '_token' => csrf_token(),
            ])
            ->assertStatus(302)
            ->assertRedirect(route('pump_series.index'));
        $this->assertSoftDeleted($brand);
    }
}
