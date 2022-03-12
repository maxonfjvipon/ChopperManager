<?php

namespace Modules\User\Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Modules\Project\Entities\Currency;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpSeries;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Modules\User\Entities\User;
use Modules\User\Http\Endpoints\ChangePasswordEndpoint;
use Modules\User\Http\Endpoints\ProfileIndexEndpoint;
use Modules\User\Http\Endpoints\ProfileUpdateEndpoint;
use Modules\User\Http\Endpoints\UpdateDiscountsEndpoint;
use Modules\User\Http\Requests\DiscountUpdateRequest;
use Modules\User\Http\Requests\UserPasswordUpdateRequest;
use Modules\User\Traits\UserAttributes;
use Tests\TestCase;

class UserProfileEndpointTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     * @see ProfileIndexEndpoint
     * @author Max Trunnikov
     */
    public function test_if_unauthorized_user_cannot_get_access_to_profile()
    {
        $this->get(route('profile.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * @return void
     * @see ProfileIndexEndpoint
     * @see UserAttributes
     * @author Max Trunnikov
     */
    public function test_profile_index_endpoint()
    {
        $user = User::fakeWithRole();
        $series = PumpSeries::factory()->count(4)->create();
        Discount::updateForUser($series->pluck('id')->all(), $user);
        $this->actingAs($user)
            ->get(route('profile.index'))
            ->assertStatus(200)
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('User::Profile', false)
                ->has('user', fn(AssertableInertia $page) => $page
                    ->has('data', fn(AssertableInertia $page) => $page
                        ->where('id', $user->id)
                        ->where('organization_name', $user->organization_name)
                        ->where('itn', $user->itn)
                        ->where('phone', $user->phone)
                        ->where('country_id', $user->country_id)
                        ->where('currency_id', $user->currency_id)
                        ->where('city', $user->city)
                        ->where('postcode', $user->postcode)
                        ->where('first_name', $user->first_name)
                        ->where('middle_name', $user->middle_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('business_id', $user->business_id)
                    )
                )
                ->has('currencies')
                ->count('currencies', Currency::allOrCached()->count())
                ->has('currencies.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                )
                ->has('businesses')
                ->count('businesses', Business::allOrCached()->count())
                ->has('businesses.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                )
                ->has('countries')
                ->count('countries', Country::allOrCached()->count())
                ->has('countries.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                    ->has('currency_id')
                    ->has('code')
                )
                ->has('discounts')
                ->count('discounts', $user->discounts()->whereDiscountableType('pump_series')->count())
                ->has('discounts.0', fn(AssertableInertia $page) => $page
                    ->has('key')
                    ->has('discountable_id')
                    ->has('discountable_type')
                    ->where('user_id', $user->id)
                    ->has('name')
                    ->has('value')
                    ->has('children')
                    ->has('children.0', fn(AssertableInertia $page) => $page
                        ->has('key')
                        ->has('discountable_id')
                        ->has('discountable_type')
                        ->where('user_id', $user->id)
                        ->has('name')
                        ->has('value')
                    )
                )
            );
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see ProfileUpdateEndpoint
     */
    public function test_profile_update_endpoint()
    {
        $user = User::fakeWithRole();
        $newUserData = [
            'organization_name' => 'test_organization',
            'itn' => $this->faker->unique()->text(12),
            'phone' => $this->faker->unique()->text(12),
            'country_id' => Country::allOrCached()->random()->id,
            'city' => $this->faker->unique()->city(),
            'currency_id' => Currency::allOrCached()->random()->id,
            'first_name' => $this->faker()->unique()->firstName(),
            'middle_name' => $this->faker()->unique()->lastName(),
            'last_name' => $this->faker()->unique()->lastName(),
            'email' => $this->faker()->unique()->safeEmail(),
            'business_id' => Business::allOrCached()->random()->id,
        ];
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.update'), array_merge(
                ['_token' => csrf_token()],
                $newUserData
            ))
            ->assertStatus(302)
            ->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('users', array_merge(
            ['id' => $user->id],
            $newUserData
        ));
    }

    /**
     * @return void
     * @see ChangePasswordEndpoint
     * @author Max Trunnikov
     */
    public function test_change_password_endpoint()
    {
        $user = User::fakeWithRole();
        $newPassword = 'new password 123';
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.password.change'), [
                '_token' => csrf_token(),
                'current_password' => 'password',
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ])
            ->assertStatus(302)
            ->assertRedirect(route('profile.index'));
        $this->assertEquals(
            true,
            Hash::check($newPassword, $user->password)
        );
    }

    /**
     * @return void
     * @see ChangePasswordEndpoint
     * @see UserPasswordUpdateRequest
     * @author Max Trunnikov
     */
    public function test_user_cannot_change_password_without_passing_an_old_one()
    {
        $user = User::fakeWithRole();
        $newPassword = 'new password';
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.password.change'), [
                '_token' => csrf_token(),
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ])
            ->assertRedirect(route('profile.index'))
            ->assertStatus(302);
        $this->assertNotEquals(
            Hash::make($newPassword),
            $user->password
        );
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password' => $user->password
        ]);
    }

    /**
     * @return void
     * @see ChangePasswordEndpoint
     * @see UserPasswordUpdateRequest
     * @author Max Trunnikov
     */
    public function test_user_cannot_change_password_with_incorrect_an_old_one()
    {
        $user = User::fakeWithRole();
        $newPassword = 'new password';
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.password.change'), [
                '_token' => csrf_token(),
                'current_password' => 'invalid password',
                'password' => $newPassword,
                'password_confirmation' => $newPassword
            ])
            ->assertRedirect(route('profile.index'))
            ->assertStatus(302);
        $this->assertNotEquals(
            Hash::make($newPassword),
            $user->password
        );
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password' => $user->password
        ]);
    }

    /**
     * @return void
     * @see ChangePasswordEndpoint
     * @see UserPasswordUpdateRequest
     * @author Max Trunnikov
     */
    public function test_user_cannot_update_password_if_new_password_is_not_confirmed()
    {
        $user = User::fakeWithRole();
        $newPassword = 'new password';
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.password.change'), [
                '_token' => csrf_token(),
                'current_password' => 'password',
                'password' => $newPassword,
                'password_confirmation' => 'invalid confirmation'
            ])
            ->assertRedirect(route('profile.index'))
            ->assertStatus(302);
        $this->assertNotEquals(
            Hash::make($newPassword),
            $user->password
        );
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password' => $user->password
        ]);
    }

    /**
     * @return void
     * @see UpdateDiscountsEndpoint
     * @see DiscountUpdateRequest
     * @author Max Trunnikov
     */
    public function test_update_series_discount()
    {
        $user = User::fakeWithRole();
        $series = PumpSeries::factory()->create();
        Discount::updateForUser([$series->id], $user);
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.discount.update'), [
                '_token' => csrf_token(),
                'discountable_id' => $series->id,
                'discountable_type' => 'pump_series',
                'user_id' => $user->id,
                'value' => 50
            ])
            ->assertStatus(302)
            ->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('discounts', [
            'discountable_id' => $series->id,
            'discountable_type' => 'pump_series',
            'user_id' => $user->id,
            'value' => 50
        ]);
    }

    /**
     * @return void
     * @see UpdateDiscountsEndpoint
     * @see DiscountUpdateRequest
     * @author Max Trunnikov
     */
    public function test_update_brand_discount()
    {
        $user = User::fakeWithRole();
        $brand = PumpBrand::factory()->create();
        $series = PumpSeries::factory()->count(2)->create(['brand_id' => $brand->id]);
        Discount::updateForUser($series->pluck('id')->all(), $user);
        $user->discounts()->update(['value' => 0]);
        $this->startSession()
            ->actingAs($user)
            ->from(route('profile.index'))
            ->post(route('profile.discount.update'), [
                '_token' => csrf_token(),
                'discountable_id' => $brand->id,
                'discountable_type' => 'pump_brand',
                'user_id' => $user->id,
                'value' => 50
            ])
            ->assertStatus(302)
            ->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('discounts', [
            'discountable_id' => $brand->id,
            'discountable_type' => 'pump_brand',
            'user_id' => $user->id,
            'value' => 50
        ]);
        $this->assertDatabaseHas('discounts', [
            'discountable_id' => $series->first()->id,
            'discountable_type' => 'pump_series',
            'user_id' => $user->id,
            'value' => 50
        ]);
        $this->assertDatabaseHas('discounts', [
            'discountable_id' => $series->last()->id,
            'discountable_type' => 'pump_series',
            'user_id' => $user->id,
            'value' => 50
        ]);
    }
}
