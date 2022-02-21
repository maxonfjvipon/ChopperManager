<?php

namespace Modules\Project\Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Modules\Project\Entities\Project;
use Modules\User\Entities\User;
use Tests\TestCase;

class ProjectsEndpointsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_projects_index()
    {
        $user = User::find(1);
        Project::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
                'status_id' => 1,
            ]);
        $this->actingAs($user)
            ->get(route('projects.index'))
            ->assertStatus(200)
            ->assertInertia(fn($page) => $page
                ->component('Project::Projects/Index', false)
                ->has('projects', Auth::user()->projects()->count())
                ->has('projects.0', fn($page) => $page
                    ->has('id')
                    ->has('name')
                    ->has('selections_count')
                    ->has('created_at')
                    ->has('selections')
                    ->etc()
                    ->has('selections.0', fn($page) => $page
                        ->has('id')
                        ->has('project_id')
                        ->has('selected_pump_name')
                        ->has('flow')
                        ->has('head')
                    )
                )
            )->assertStatus(200);
    }

    public function test_projects_create()
    {
        $this->actAs()
            ->get(route('projects.create'))
            ->assertInertia(fn($page) => $page
                ->component('Project::Projects/Create', false)
            )->assertStatus(200);
    }

    public function test_projects_show()
    {
        $this->actAs()
            ->get(route('projects.show', 1))
            ->assertInertia(fn($page) => $page
                ->component('Project::Projects/Show', false)
                ->has('project', fn($page) => $page
                    ->has('data', fn($page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('selections')
                        ->has('selections.0', fn($page) => $page
                            ->hasAll([
                                'id', 'pump_id', 'article_num', 'created_at',
                                'flow', 'head', 'selected_pump_name', 'discounted_price',
                                'total_discounted_price', 'rated_power', 'total_rated_power',
                                'pumpable_type'
                            ])
                        )
                    )
                )
            )->assertStatus(200);
    }

    public function test_projects_edit()
    {
        $this->actAs()
            ->get(route('projects.edit', 1))
            ->assertInertia(fn($page) => $page
                ->has('project', fn($page) => $page
                    ->has('data', fn($page) => $page
                        ->hasAll(['id', 'name'])
                    )
                )
            )->assertStatus(200);
    }

    public function test_projects_store()
    {
        $this->actAs()
            ->post(route('projects.store'), [
                'name' => 'project store test'
            ])
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $project = Project::latest()->first();
        $this->assertEquals('project store test', $project->name);
        $this->assertEquals($this->user->id, $project->user_id);
        Project::latest()->first()->forceDelete();
    }

    public function test_projects_update()
    {
        $project = Project::create([
            'name' => 'test project',
            'user_id' => $this->user->id,
        ]);
        $this->actAs()
            ->put(route('projects.update', $project->id), [
                'name' => 'project update test'
            ])->assertStatus(302)
            ->assertRedirect(route('projects.index'));
        $updatedProject = Project::find($project->id);
        $this->assertEquals('project update test', $updatedProject->name);
        $updatedProject->forceDelete();
    }

    public function test_projects_destroy()
    {
        $this->actAs()
            ->delete(route('projects.destroy', 1))
            ->assertStatus(302)
            ->assertRedirect('/');
        $deletedProject = Project::withTrashed()->find(1);
        $this->assertNotNull($deletedProject);
        $this->assertNotNull($deletedProject->deleted_at);
        $deletedProject->restore();
    }
}
