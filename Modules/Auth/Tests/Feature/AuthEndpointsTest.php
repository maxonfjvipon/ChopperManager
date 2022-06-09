<?php

namespace Modules\Auth\Tests\Feature;

use Exception;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\User;
use Tests\TestCase;

class AuthEndpointsTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     * @see EpLogin
     * @author Max Trunnikov
     */
    public function test_login_page_endpoint()
    {
        $this->get(route('login'))
            ->assertSuccessful()
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component("Auth::Login", false)
            )->assertStatus(200);
    }

    /**
     * @return void
     * @see EpRegister
     * @author Max Trunnikov
     */
    public function test_register_page_endpoint()
    {
        $this->get(route('register'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Auth::Register', false)
                ->has('businesses')
                ->has('businesses.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                )
                ->has('countries')
                ->has('countries.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                )
            )->assertStatus(200);
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpLoginAttempt
     */
    public function test_login_attempt_endpoint()
    {
        $user = User::factory()->create();
        $this->startSession()
            ->expectsEvents(Login::class)
            ->post(route('login.attempt'), [
                '_token' => csrf_token(),
                'email' => $user->email,
                'password' => 'password'
            ])
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @return void
     * @see EpLoginAttempt
     * @author Max Trunnikov
     */
    public function test_if_user_cannot_login_with_invalid_password()
    {
        $this->startSession()
            ->post(route('login.attempt'), [
                '_token' => csrf_token(),
                'email' => User::factory()->create()->email,
                'password' => 'invalid'
            ])
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * @return void
     * @see EpLoginAttempt
     * @author Max Trunnikov
     */
    public function test_if_user_cannot_login_with_invalid_email()
    {
        User::factory()->create(['email' => 'some_email@test.com']);
        $this->startSession()
            ->post(route('login.attempt'), [
                '_token' => csrf_token(),
                'email' => 'invalid@test.com',
                'password' => 'password'
            ])
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * @return void
     * @see EpLogin
     * @author Max Trunnikov
     */
    public function test_if_authenticated_user_cannot_see_login_page()
    {
        $this->actingAs($user = User::factory()->create())
            ->get(route('login'))
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
    }

    /**
     * @return void
     * @see EpRegister
     * @author Max Trunnikov
     */
    public function test_if_authenticated_user_cannot_see_register_page()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpRegisterAttempt
     */
    public function test_register_attempt_endpoint()
    {
        $this->expectsEvents(Registered::class);
        $this->startSession()
            ->post(route('register.attempt'), [
                '_token' => csrf_token(),
                'organization_name' => $this->faker->name(),
                'itn' => '123456789102',
                'email' => $this->faker->safeEmail(),
                'password' => 'password',
                'password_confirmation' => 'password',
                'phone' => '89991231212',
                'first_name' => $this->faker->firstName(),
                'middle_name' => $this->faker->lastName(),
                'last_name' => null,
                'city' => $this->faker->city(),
                'postcode' => $this->faker->postcode(),
                'country_id' => Country::allOrCached()->random()->id,
                'business_id' => Business::allOrCached()->random()->id,
            ])
            ->assertStatus(302)
            ->assertRedirect(route('verification.notice'));
        // TODO: test if verification email was sent
    }

    /**
     * @return void
     * @throws Exception
     * @see EpRegisterAttempt
     * @author Max Trunnikov
     */
    public function test_if_user_with_the_email_exists()
    {
        $testEmail = 'test@test.com';
        User::factory()->create([
            'email' => $testEmail
        ]);
        $this->startSession()
            ->post(route('register.attempt'), [
                '_token' => csrf_token(),
                'organization_name' => $this->faker->name(),
                'itn' => '123456789102',
                'email' => $testEmail,
                'password' => 'password',
                'password_confirmation' => 'password',
                'phone' => '89991231212',
                'first_name' => $this->faker->firstName(),
                'middle_name' => $this->faker->lastName(),
                'last_name' => null,
                'city' => $this->faker->city(),
                'postcode' => $this->faker->postcode(),
                'country_id' => Country::allOrCached()->random()->id,
                'business_id' => Business::allOrCached()->random()->id,
            ])
            ->assertSessionHasErrors('email')
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    /**
     * @return void
     * @see EpLogout
     * @author Max Trunnikov
     */
    public function test_logout_endpoint()
    {
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->from(route('projects.index'))
            ->post(route('logout'), [
                '_token' => csrf_token()
            ])
            ->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertNull(Auth::user());
    }
}
