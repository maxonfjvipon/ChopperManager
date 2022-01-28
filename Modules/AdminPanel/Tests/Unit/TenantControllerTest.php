<?php

namespace Modules\AdminPanel\Tests\Unit;

use Inertia\Testing\Assert;
use Modules\AdminPanel\Entities\Admin;
use Modules\AdminPanel\Entities\SelectionType;
use Modules\AdminPanel\Entities\Tenant;
use Modules\AdminPanel\Entities\TenantType;
use Tests\TestCase;

class TenantControllerTest extends TestCase
{
    public function test_tenants_index()
    {
        $pm = Tenant::find(1);
        $this->actingAs(Admin::find(1), 'admin')
            ->get('http://admin.localhost:8000/tenants')
            ->assertInertia(fn($page) => $page
                ->component('AdminPanel::Tenants/Index', false)
                ->has('tenants', Tenant::count())
                ->has('tenants.0', fn($page) => $page
                    ->where('id', 1)
                    ->where('name', $pm->name)
                    ->where('domain', $pm->domain)
                    ->where('database', $pm->database)
                    ->where('is_active', $pm->is_active)
                    ->where('has_registration', $pm->has_registration)
                    ->etc()
                    ->has('type', fn($page) => $page
                        ->where('id', $pm->type->id)
                        ->where('name', $pm->type->name)
                    )
                )
            )->assertStatus(200);
    }

    public function test_tenants_create()
    {
        $this->actingAs(Admin::find(1), 'admin')
            ->get('http://admin.localhost:8000/tenants/create')
            ->assertInertia(fn($page) => $page
                ->component("AdminPanel::Tenants/Create", false)
                ->has('tenant_types', TenantType::count() - 1)
                ->has('selection_types', SelectionType::count())
                ->has('default_selection_types', SelectionType::count())
            )->assertStatus(200);
    }
}
