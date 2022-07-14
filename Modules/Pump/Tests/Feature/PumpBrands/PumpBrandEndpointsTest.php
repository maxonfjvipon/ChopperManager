<?php

namespace Modules\Pump\Tests\Feature\PumpBrands;

use Inertia\Testing\AssertableInertia;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsCreateEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsDestroyEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsEditEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsRestoreEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsStoreEndpoint;
use Modules\Pump\Http\Endpoints\PumpBrand\PumpBrandsUpdateEndpoint;
use Modules\User\Entities\User;
use Tests\TestCase;

class PumpBrandEndpointsTest extends TestCase
{
    /**
     * @return void
     *
     * @see PumpBrandsStoreEndpoint
     * @see PumpBrandsCreateEndpoint
     * @see PumpBrandsEditEndpoint
     * @see PumpBrandsDestroyEndpoint
     * @see PumpBrandsRestoreEndpoint
     * @see PumpBrandsUpdateEndpoint
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
     * @see PumpBrandsCreateEndpoint
     *
     * @author Max Trunnikov
     */
    public function testPumpBrandsCreateEndpoint()
    {
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('pump_brands.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Pump::PumpBrands/Create', false)
            );
    }

    /**
     * @return void
     *
     * @see PumpBrandsStoreEndpoint
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
     * @see PumpBrandsEditEndpoint
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
                ->component('Pump::PumpBrands/Edit', false)
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
     * @see PumpBrandsRestoreEndpoint
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
     * @see PumpBrandsUpdateEndpoint
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
     * @see PumpBrandsDestroyEndpoint
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
