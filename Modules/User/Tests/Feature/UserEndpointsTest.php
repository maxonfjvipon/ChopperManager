<?php

namespace Modules\User\Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\Fluent\Concerns\Has;
use Inertia\Testing\AssertableInertia;
use Modules\Project\Entities\Project;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Modules\User\Entities\User;
use Modules\User\Entities\UserAndPumpSeries;
use Modules\User\Entities\UserAndSelectionType;
use Modules\User\Http\Endpoints\EpDetailUserStatistics;
use Modules\User\Http\Endpoints\EpCreateOrEditUser;
use Modules\User\Http\Endpoints\EpEditUser;
use Modules\User\Http\Endpoints\EpUsers;
use Modules\User\Http\Endpoints\EpUsersStatistics;
use Modules\User\Http\Endpoints\EpStoreUser;
use Modules\User\Http\Endpoints\EpUpdateUser;
use Modules\User\Http\Middleware\CheckUserIsActive;
use Modules\User\Support\UsersFilterData;
use Tests\TestCase;

class UserEndpointsTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     * @see EpUsers
     * @see EpEditUser
     * @see EpCreateOrEditUser
     * @see EpUsersStatistics
     * @author Max Trunnikov
     */
    public function test_if_regular_user_cannot_get_access_to_users_pages()
    {
        $user = User::fakeWithRole();
        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();
        $this->actingAs($user)
            ->get(route('users.edit', $user->id))
            ->assertForbidden();
        $this->actingAs($user)
            ->get(route('users.create', $user->id))
            ->assertForbidden();
        $this->actingAs($user)
            ->get(route('users.statistics'))
            ->assertForbidden();
    }

    /**
     * @return void
     * @see EpUsers
     * @author Max Trunnikov
     */
    public function test_users_index_endpoint()
    {
        $user = User::fakeWithRole('SuperAdmin');
        User::factory()->count(5)->create();
        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('User::Index', false)
                ->has('filter_data', fn(AssertableInertia $page) => $page
                    ->has('businesses')
                    ->count('businesses', Business::allOrCached()->count())
                    ->has('businesses.0', fn(AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                )
                ->has('users')
                ->count('users', User::count())
                ->has('users.0', fn(AssertableInertia $page) => $page
                    ->hasAll([
                        'id', 'key', 'created_at', 'organization_name',
                        'full_name', 'phone', 'email', 'business',
                        'country', 'city', 'is_active'
                    ])
                )
            )->assertStatus(200);
    }

    /**
     * @param AssertableInertia $page
     * @return AssertableJson|AssertableInertia|Has
     * @throws Exception
     * @see UsersFilterData
     * @author Max Trunnikov
     */
    private function assertInertiaUsersFilterData(AssertableInertia $page): AssertableJson|AssertableInertia|Has
    {
        return $page
            ->has('selection_types')
            ->count('selection_types', SelectionType::allOrCached()->count())
            ->has('businesses')
            ->count('businesses', Business::allOrCached()->count())
            ->has('series')
            ->count('series', PumpSeries::count())
            ->has('series.0', fn(AssertableInertia $page) => $page
                ->has('id')
                ->has('name')
            )
            ->has('countries')
            ->count('countries', Country::allOrCached()->count())
            ->has('countries.0', fn(AssertableInertia $page) => $page
                ->has('id')
                ->has('name')
            );
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpCreateOrEditUser
     */
    public function test_users_create_endpoint()
    {
        PumpSeries::factory()->count(3)->create();
        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('users.create'))
            ->assertInertia(fn(AssertableInertia $page) => $this->assertInertiaUsersFilterData($page)
                ->component('User::Create', false)
            )->assertStatus(200);
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpEditUser
     */
    public function test_users_edit_endpoint()
    {
        $seriesCount = 3;
        $user = User::fakeWithRole();
        $series = PumpSeries::factory()->count($seriesCount)->create();
        UserAndPumpSeries::updateAvailableSeriesForUser($series->pluck('id')->all(), $user);
        UserAndSelectionType::updateAvailableSelectionTypesForUser(SelectionType::allOrCached()->pluck('id')->all(), $user);

        $this->actingAs(User::fakeWithRole('SuperAdmin'))
            ->get(route('users.edit', $user->id))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('User::Edit', false)
                ->has('user', fn(AssertableInertia $page) => $page
                    ->has('data', fn(AssertableInertia $page) => $page
                        ->where('id', $user->id)
                        ->where('organization_name', $user->organization_name)
                        ->where('itn', $user->itn)
                        ->where('phone', $user->phone)
                        ->where('country_id', $user->country_id)
                        ->where('city', $user->city)
                        ->where('postcode', $user->postcode)
                        ->where('first_name', $user->first_name)
                        ->where('middle_name', $user->middle_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('business_id', $user->business_id)
                        ->where('is_active', $user->is_active)
                        ->hasAll(['available_series_ids', 'available_selection_type_ids'])
                        ->count('available_series_ids', $seriesCount)
                        ->count('available_selection_type_ids', SelectionType::allOrCached()->count())
                    )
                )
                ->has('filter_data', fn(AssertableInertia $page) => $this->assertInertiaUsersFilterData($page))
            )->assertStatus(200);
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpStoreUser
     */
    public function test_if_client_cannot_store_user()
    {
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->post(route('users.store'), [
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
                'is_active' => true,
                'email_verified' => true,
                'available_selection_type_ids' => [],
                'available_series_ids' => []
            ])->assertForbidden();
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpStoreUser
     */
    public function test_users_store_endpoint()
    {
        $series = PumpSeries::factory()->count(2)->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('users.store'), [
                '_token' => csrf_token(),
                'organization_name' => 'test_organization',
                'itn' => '123456789102',
                'email' => 'test@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'phone' => '89991231212',
                'first_name' => 'test',
                'middle_name' => 'test',
                'last_name' => null,
                'city' => $this->faker->city(),
                'postcode' => $this->faker->postcode(),
                'country_id' => Country::allOrCached()->random()->id,
                'business_id' => Business::allOrCached()->random()->id,
                'is_active' => true,
                'email_verified' => true,
                'available_selection_type_ids' => [1, 2],
                'available_series_ids' => $series->pluck('id')->all()
            ])
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));
        $user = User::firstWhere('email', 'test@test.com');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'organization_name' => 'test_organization',
            'itn' => '123456789102',
            'email' => 'test@test.com',
            'first_name' => 'test',
            'middle_name' => 'test',
        ]);
        $this->assertDatabaseHas('model_has_roles', [
            'model_id' => $user->id,
            'role_id' => 3,
        ]);
        $this->assertDatabaseHas('users_and_pump_series', [
            'user_id' => $user->id,
            'series_id' => $series->first()->id
        ]);
        $this->assertDatabaseHas('users_and_pump_series', [
            'user_id' => $user->id,
            'series_id' => $series->last()->id
        ]);
        $this->assertDatabaseHas('users_and_selection_types', [
            'user_id' => $user->id,
            'type_id' => 1
        ]);
        $this->assertDatabaseHas('users_and_selection_types', [
            'user_id' => $user->id,
            'type_id' => 2
        ]);
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpUpdateUser
     */
    public function test_if_client_cannot_update_user()
    {
        $user = User::fakeWithRole();
        $this->startSession()
            ->actingAs(User::fakeWithRole())
            ->put(route('users.update', $user->id), [
                '_token' => csrf_token(),
                'organization_name' => 'test_organization',
                'itn' => '123456789102',
                'email' => 'test@test.com',
                'phone' => '89991231212',
                'first_name' => 'test',
                'middle_name' => 'test',
                'last_name' => null,
                'city' => $this->faker->city(),
                'postcode' => $this->faker->postcode(),
                'country_id' => Country::allOrCached()->random()->id,
                'business_id' => Business::allOrCached()->random()->id,
                'is_active' => true,
                'available_selection_type_ids' => [],
                'available_series_ids' => []
            ])->assertForbidden();
    }

    /**
     * @return void
     * @throws Exception
     * @author Max Trunnikov
     * @see EpUpdateUser
     */
    public function test_users_update_endpoint()
    {
        $user = User::fakeWithRole();
        $series = PumpSeries::factory()->count(2)->create();
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->put(route('users.update', $user->id), [
                '_token' => csrf_token(),
                'organization_name' => 'test_organization',
                'itn' => '123456789102',
                'email' => 'test@test.com',
                'phone' => '89991231212',
                'first_name' => 'test',
                'middle_name' => 'test',
                'last_name' => null,
                'city' => 'test_city',
                'postcode' => 'postcode',
                'country_id' => 1,
                'business_id' => 1,
                'is_active' => false,
                'available_selection_type_ids' => [1, 2],
                'available_series_ids' => $series->pluck('id')->all()
            ])
            ->assertStatus(302)
            ->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'organization_name' => 'test_organization',
            'itn' => '123456789102',
            'email' => 'test@test.com',
            'phone' => '89991231212',
            'first_name' => 'test',
            'middle_name' => 'test',
            'last_name' => null,
            'city' => 'test_city',
            'postcode' => 'postcode',
            'country_id' => 1,
            'business_id' => 1,
            'is_active' => false,
            'email_verified_at' => $user->email_verified_at
        ]);
        $this->assertDatabaseHas('users_and_pump_series', [
            'user_id' => $user->id,
            'series_id' => $series->first()->id
        ]);
        $this->assertDatabaseHas('users_and_pump_series', [
            'user_id' => $user->id,
            'series_id' => $series->last()->id
        ]);
        $this->assertDatabaseHas('users_and_selection_types', [
            'user_id' => $user->id,
            'type_id' => 1
        ]);
        $this->assertDatabaseHas('users_and_selection_types', [
            'user_id' => $user->id,
            'type_id' => 2
        ]);
    }

    /**
     * @return void
     * @see EpUsersStatistics
     * @author Max Trunnikov
     */
    public function test_users_statistics_endpoint()
    {
        $usersCount = 10;
        $user = User::fakeWithRole('SuperAdmin');
        $users = User::factory()->count($usersCount - 1)->create();
        $allUsers = collect([$user, ...$users]);
        $selectionsCount = 2;
        foreach ($allUsers as $_user) {
            $project = Project::fakeForUser($_user);
            Selection::fakeForProject($project, $selectionsCount);
        }
        Project::all()->random()->delete();

        $this->actingAs($user)
            ->get(route('users.statistics'))
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('User::Statistics', false)
                ->has('filter_data', fn(AssertableInertia $page) => $page
                    ->has('countries')
                    ->count('countries', Country::allOrCached()
                        ->whereIn('id', $allUsers->pluck('country_id')->all())
                        ->count()
                    )
                    ->has('businesses')
                    ->count('businesses', Business::allOrCached()
                        ->whereIn('id', $allUsers->pluck('business_id')->all())
                        ->count()
                    )
                    ->has('cities')
                    ->count('cities', $allUsers->unique('city')->pluck('city')->count())
                    ->has('organizations')
                    ->count('organizations', $allUsers->unique('organization_name')->pluck('organization_name')->count())
                )
                ->has('users')
                ->count('users', $usersCount)
                ->has('users.0', fn(AssertableInertia $page) => $page
                    ->has('id')
                    ->has('key')
                    ->has('last_login_at')
                    ->has('organization_name')
                    ->has('full_name')
                    ->has('business')
                    ->has('country')
                    ->has('city')
                    ->has('projects_count')
                    ->has('total_projects_price')
                    ->has('avg_projects_price')
                )
            )
            ->assertStatus(200);
    }

    /**
     * @return void
     * @see EpDetailUserStatistics
     * @author Max Trunnikov
     */
    public function test_client_cannot_get_access_to_user_detail_statistics()
    {
        $user = User::fakeWithRole();
        $this->startSession()
            ->actingAs($user)
            ->post(route('users.statistics.detail', $user->id), [
                '_token' => csrf_token()
            ])->assertForbidden();
    }

    /**
     * @return void
     * @see EpDetailUserStatistics
     * @author Max Trunnikov
     */
    public function test_user_detail_statistics_endpoint()
    {
        $user = User::fakeWithRole();
        Project::fakeForUser($user, 3);
        $series = PumpSeries::factory()->count(3)->create();
        Discount::updateForUser($series->pluck('id')->all(), $user);
        $this->startSession()
            ->actingAs(User::fakeWithRole('SuperAdmin'))
            ->post(route('users.statistics.detail', $user->id), [
                '_token' => csrf_token()
            ])
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('id', $user->id)
                ->where('full_name', $user->full_name)
                ->has('discounts')
                ->count('discounts', $user->discounts()->whereDiscountableType('pump_series')->count())
                ->has('discounts.0', fn(AssertableJson $json) => $json
                    ->has('key')
                    ->has('discountable_id')
                    ->has('discountable_type')
                    ->where('user_id', $user->id)
                    ->has('name')
                    ->has('value')
                    ->has('children')
                    ->has('children.0', fn(AssertableJson $json) => $json
                        ->has('key')
                        ->has('discountable_id')
                        ->has('discountable_type')
                        ->where('user_id', $user->id)
                        ->has('name')
                        ->has('value')
                    )
                )
                ->has('projects')
                ->count('projects', $user->projects()->count())
                ->has('projects.0', fn(AssertableJson $json) => $json
                    ->has('id')
                    ->has('created_at')
                    ->has('name')
                    ->has('selections_count')
                    ->where('user_id', $user->id)
                    ->etc()
                )
            );
    }

    /**
     * @return void
     * @see CheckUserIsActive
     * @author Max Trunnikov
     */
    public function test_if_user_is_active()
    {
        $this->actingAs(User::fakeWithRole())
            ->get(route('projects.index'))
            ->assertSuccessful();
    }

    /**
     * @return void
     * @see CheckUserIsActive
     * @author Max Trunnikov
     */
    public function test_if_user_is_not_active()
    {
        $user = User::factory()->create([
            'is_active' => false
        ]);
        $user->assignRole('Client');
        $this->actingAs($user)
            ->get(route('projects.index'))
            ->assertStatus(401);
        $this->assertNull(Auth::user());
    }
}
