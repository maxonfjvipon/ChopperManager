<?php

namespace Modules\Project\Tests\Feature;

use Inertia\Testing\AssertableInertia;
use Modules\Project\Entities\Project;
use Modules\Project\Entities\ProjectDeliveryStatus;
use Modules\Project\Entities\ProjectStatus;
use Modules\Project\Http\Endpoints\EpCloneProject;
use Modules\Project\Http\Endpoints\EpCreateOrEditProject;
use Modules\Project\Http\Endpoints\EpCreateProject;
use Modules\Project\Http\Endpoints\EpDestroyProject;
use Modules\Project\Http\Endpoints\EpExportProject;
use Modules\Project\Http\Endpoints\EpProjects;
use Modules\Project\Http\Endpoints\EpProjectsStatistics;
use Modules\Project\Http\Endpoints\EpRestoreProject;
use Modules\Project\Http\Endpoints\EpShowProject;
use Modules\Project\Http\Endpoints\EpStoreProject;
use Modules\Project\Http\Endpoints\EpUpdateProject;
use Modules\Project\Takes\TkUpdateProject;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\User;
use Tests\TestCase;

class ProjectsEndpointsTest extends TestCase
{
    /**
     * @return void
     *
     * @see EpProjects
     *
     * @author Max Trunnikov
     */
    public function testProjectsIndexEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection = Selection::fakeForProject($project);
        $this->actingAs($user)
            ->get(route('projects.index'))
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Project::Projects/Index', false)
                ->has('projects')
                ->has('projects.0', function (AssertableInertia $page) use ($project, $selection) {
                    return $page->has('selections')
                        ->where('id', $project->id)
                        ->where('name', $project->name)
                        ->where('selections_count', $project->selections()->count())
                        ->has('created_at')
                        ->etc()
                        ->has('selections.0', function (AssertableInertia $page) use ($selection) {
                            return $page->where('id', $selection->id)
                                ->where('project_id', $selection->project_id)
                                ->where('selected_pump_name', $selection->selected_pump_name)
                                ->has('flow')
                                ->has('head');
                        });
                })
            )->assertStatus(200);
    }

    /**
     * @return void
     *
     * @see EpProjects
     *
     * @author Max Trunnikov
     */
    public function testIfUserCanSeeOnlyHisProjects()
    {
        $user1 = User::fakeWithRole();
        $user2 = User::fakeWithRole();
        Project::fakeForUser($user1, 3);
        Project::fakeForUser($user2);
        $this->actingAs($user2)
            ->get(route('projects.index'))
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Project::Projects/Index', false)
                ->has('projects')
                ->count('projects', 1)
            );
        $this->actingAs($user1)
            ->get(route('projects.index'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Project::Projects/Index', false)
                ->has('projects')
                ->count('projects', 3)
            );
    }

    /**
     * @return void
     *
     * @see EpCreateProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsCreateEndpoint()
    {
        $this->actingAs(User::fakeWithRole())
            ->get(route('projects.create'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Project::Projects/Create', false)
            )->assertStatus(200);
    }

    /**
     * @return void
     *
     * @see EpShowProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsShowEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        Selection::fakeForProject($project, 2);
        $this->actingAs($user)
            ->get(route('projects.show', $project->id))
            ->assertInertia(function (AssertableInertia $page) use ($project) {
                return $page
                    ->component('Project::Projects/Show', false)
                    ->has('project', fn (AssertableInertia $page) => $page
                        ->has('data', fn (AssertableInertia $page) => $page
                            ->where('id', $project->id)
                            ->where('name', $project->name)
                            ->has('selections')
                            ->count('selections', 2)
                            ->has('selections.0', fn ($page) => $page
                                ->hasAll([
                                    'id', 'pump_id', 'article_num', 'created_at',
                                    'flow', 'head', 'selected_pump_name', 'discounted_price',
                                    'total_discounted_price', 'rated_power', 'total_rated_power',
                                    'pumpable_type',
                                ])
                            )
                        )
                    );
            })->assertStatus(200);
    }

    /**
     * @return void
     *
     * @see EpCreateOrEditProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsEditEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $this->actingAs($user)
            ->get(route('projects.edit', $project->id))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('project', fn (AssertableInertia $page) => $page
                    ->has('data', fn (AssertableInertia $page) => $page
                        ->where('id', $project->id)
                        ->where('name', $project->name)
                    )
                )
            )->assertStatus(200);
    }

    /**
     * @return void
     *
     * @see EpStoreProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsStoreEndpoint()
    {
        $user = User::fakeWithRole();
        $testName = 'project store test name';
        $this->startSession()
            ->actingAs($user)
            ->post(route('projects.store'), [
                'name' => $testName,
                '_token' => csrf_token(),
            ])
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'name' => $testName,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @return void
     *
     * @see EpUpdateProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsUpdateEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'status_id' => 1,
            'delivery_status_id' => 1,
            'comment' => 'Test',
        ]);
        $nameToUpdate = 'new project name';
        $this->startSession()
            ->actingAs($user)
            ->put(route('projects.update', $project->id), [
                'name' => $nameToUpdate,
                'status_id' => 2,
                'delivery_status_id' => 2,
                'comment' => 'Foo',
                '_token' => csrf_token(),
            ])
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => $nameToUpdate,
            'user_id' => $user->id,
            'status_id' => 2,
            'delivery_status_id' => 2,
            'comment' => 'Foo',
        ]);
    }

    /**
     * @return void
     *
     * @see EpDestroyProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsDestroyEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $this->startSession()
            ->actingAs($user)
            ->delete(route('projects.destroy', $project->id), [
                '_token' => csrf_token(),
            ])
            ->assertStatus(302)
            ->assertRedirect('/');
        $this->assertSoftDeleted($project);
    }

    /**
     * @return void
     *
     * @see EpCloneProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsCloneEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $cloneName = 'project_clone_name';
        $this->startSession()
            ->actingAs($user)
            ->post(route('projects.clone', $project->id), [
                'name' => $cloneName,
                '_token' => csrf_token(),
            ])
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'name' => $cloneName,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseCount('projects', 2);
    }

    /**
     * @return void
     *
     * @see EpRestoreProject
     *
     * @author Max Trunnikov
     */
    public function testProjectsRestoreEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $project->delete();
        $this->assertNotNull($project->deleted_at);
        $this->actingAs($user)
            ->get(route('projects.restore', $project->id))
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $this->assertNotSoftDeleted($project);
    }

    /**
     * @return void
     *
     * @see EpExportProject
     * @autho Max Trunnikov
     */
    public function testExportProjectsEndpoint()
    {
        $user = User::fakeWithRole();
        $project = Project::fakeForUser($user);
        $selection1 = Selection::factory()
            ->for(Pump::factory()->state([
                'pumpable_type' => Pump::$SINGLE_PUMP,
            ]))
            ->create([
                'project_id' => $project->id,
                'pumps_count' => 2,
                'reserve_pumps_count' => 1,
                'dp_work_scheme_id' => null,
                'flow' => 10,
                'head' => 10,
            ]);
        $selection2 = Selection::factory()
            ->for(Pump::factory()->state([
                'pumpable_type' => Pump::$DOUBLE_PUMP,
            ]))
            ->create([
                'project_id' => $project->id,
                'pumps_count' => null,
                'reserve_pumps_count' => null,
                'main_pumps_counts' => null,
                'flow' => 10,
                'head' => 10,
            ]);
        $this->startSession()
            ->actingAs($user)
            ->post(route('projects.export', $project->id), [
                '_token' => csrf_token(),
                'selection_ids' => [$selection1->id, $selection2->id],
                'retail_price' => true,
                'personal_price' => true,
                'pump_image' => true,
                'pump_sizes_image' => true,
                'pump_electric_diagram_image' => true,
                'pump_cross_sectional_drawing_image' => true,
            ])
            ->assertDownload()
            ->assertStatus(200);
    }

    /**
     * @return void
     *
     * @see EpShowProject
     *
     * @author Max Trunnikov
     */
    public function testIfUserCannotSeeOtherUserProject()
    {
        $user1 = User::fakeWithRole();
        $project = Project::fakeForUser($user1);
        $user2 = User::fakeWithRole();
        $this->actingAs($user2)
            ->get(route('projects.show', $project->id))
            ->assertForbidden();
        $this->get(route('projects.edit', $project->id))
            ->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpDestroyProject
     *
     * @author Max Trunnikov
     */
    public function testIfUserCannotDeleteOtherUserProject()
    {
        $project = Project::fakeForUser(User::fakeWithRole());
        $user2 = User::fakeWithRole();
        $this->startSession()
            ->actingAs($user2)
            ->delete(route('projects.destroy', $project->id), [
                '_token' => csrf_token(),
            ])->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpRestoreProject
     *
     * @author Max Trunnikov
     */
    public function testIfUserCannotRestoreOtherUserProject()
    {
        $project = Project::fakeForUser(User::fakeWithRole());
        $this->actingAs(User::fakeWithRole())
            ->get(route('projects.restore', $project->id))
            ->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpProjectsStatistics
     *
     * @author Max Trunnikov
     */
    public function testIfClientCannotSeeProjectsStatistics()
    {
        $this->actingAs(User::fakeWithRole())
            ->get(route('projects.statistics'))
            ->assertForbidden();
    }

    /**
     * @return void
     *
     * @see EpUpdateProject
     *
     * @author Max Trunnikov
     */
    public function testIfProjectWillBeDeletedIfStatusChanged()
    {
        $user = User::fakeWithRole();
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'status_id' => 1,
            'deleted_at' => null,
        ]);
        $this->startSession()
            ->actingAs($user)
            ->from(route('projects.statistics'))
            ->put(route('projects.update', $project->id), [
                '_token' => csrf_token(),
                'name' => $project->name,
                'status_id' => 3,
            ])->assertStatus(302);
        $this->assertSoftDeleted($project);
    }

    /**
     * @return void
     *
     * @see TkUpdateProject
     * @see EpUpdateProject
     *
     * @author Max Trunnikov
     */
    public function testIfProjectWillBeRestoredIfStatusChanged()
    {
        $user = User::fakeWithRole();
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'status_id' => 1,
            'deleted_at' => now(),
        ]);
        $this->startSession()
            ->actingAs($user)
            ->from(route('projects.statistics'))
            ->put(route('projects.update', $project->id), [
                '_token' => csrf_token(),
                'name' => $project->name,
                'status_id' => 2,
            ])->assertStatus(302);
        $this->assertNotSoftDeleted($project);
    }

    /**
     * @return void
     *
     * @see EpProjectsStatistics
     *
     * @author Max Trunnikov
     */
    public function testProjectsStatisticsEndpoint()
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
            ->get(route('projects.statistics'))
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Project::Projects/Statistics', false)
                ->has('filter_data', fn (AssertableInertia $page) => $page
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
                    ->has('project_statuses')
                    ->count('project_statuses', ProjectStatus::allOrCached()->count())
                    ->has('project_statuses.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                    ->has('delivery_statuses')
                    ->count('delivery_statuses', ProjectDeliveryStatus::allOrCached()->count())
                    ->has('delivery_statuses.0', fn (AssertableInertia $page) => $page
                        ->has('text')
                        ->has('value')
                    )
                )
                ->has('project_statuses')
                ->count('project_statuses', ProjectStatus::allOrCached()->count())
                ->has('project_statuses.0', fn (AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                )
                ->has('delivery_statuses')
                ->count('delivery_statuses', ProjectDeliveryStatus::allOrCached()->count())
                ->has('delivery_statuses.0', fn (AssertableInertia $page) => $page
                    ->has('id')
                    ->has('name')
                )
                ->has('projects')
                ->count('projects', $usersCount)
                ->has('projects.0', fn (AssertableInertia $page) => $page
                    ->has('key')
                    ->has('id')
                    ->has('created_at')
                    ->has('name')
                    ->has('user_organization_name')
                    ->has('user_full_name')
                    ->has('user_business')
                    ->has('country')
                    ->has('city')
                    ->where('selections_count', $selectionsCount)
                    ->has('price')
                    ->has('status_id')
                    ->has('delivery_status_id')
                    ->has('comment')
                )
            )->assertStatus(200);
    }
}
