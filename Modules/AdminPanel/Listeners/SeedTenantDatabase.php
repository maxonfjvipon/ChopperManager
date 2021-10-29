<?php

namespace Modules\AdminPanel\Listeners;

use Illuminate\Support\Facades\Artisan;
use Modules\AdminPanel\Events\TenantCreated;

class SeedTenantDatabase
{
    /**
     * Handle the event.
     *
     * @param TenantCreated $event
     * @return void
     */
    public function handle(TenantCreated $event)
    {
        Artisan::call('module:seed', [
            'module'        => $event->tenant->type->name,
            '--database'    => 'tenant',
            '--force'       => true,
        ]);
    }
}
