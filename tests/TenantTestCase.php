<?php

namespace Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\AdminPanel\Entities\Tenant;
use Modules\PumpManager\Entities\PMUser;
use Modules\User\Entities\Userable;
use Modules\User\Traits\HasUserable;
use Tests\TestCase;

abstract class TenantTestCase extends TestCase
{
    use HasUserable;

    /**
     * @var Userable
     */
    protected Userable $user;

    /**
     * @var string|mixed
     */
    protected string $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createApplication();
        config()->set('multitenancy.landlord_database_connection_name', 'landlord_test');
        $tenant = $this->activatedTenant();
        $this->guard = $tenant->guard;
        $this->user = (new ($this->getUserClass()))->find(1);
        Auth::guard($tenant->guard)->login($this->user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Auth::guard($this->guard)->logout();
        Tenant::current()->forget();
    }

    /**
     * @return Tenant
     */
    protected function activatedTenant(): Tenant
    {
        $tenant = Tenant::find($this->tenantId());
        $tenant->makeCurrent();
        return $tenant;
    }

    /**
     * @return int
     */
    abstract protected function tenantId(): int;

    /**
     * @return TenantTestCase
     */
    protected function actAs(): TenantTestCase
    {
        return $this->actingAs($this->user, $this->guard);
    }
}
