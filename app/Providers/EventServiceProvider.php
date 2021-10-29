<?php

namespace App\Providers;

use App\Listeners\AssignRoleToRegisteredUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\AdminPanel\Events\TenantCreated;
use Modules\AdminPanel\Listeners\CreateTenantDatabase;
use Modules\AdminPanel\Listeners\DropIfExistsTenantDatabase;
use Modules\AdminPanel\Listeners\MigrateTenantDatabase;
use Modules\AdminPanel\Listeners\SeedTenantDatabase;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            AssignRoleToRegisteredUser::class,
        ],
        TenantCreated::class => [
            DropIfExistsTenantDatabase::class,
            CreateTenantDatabase::class,
            MigrateTenantDatabase::class,
            SeedTenantDatabase::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
