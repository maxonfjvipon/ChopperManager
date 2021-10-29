<?php

namespace Modules\AdminPanel\Listeners;

use Illuminate\Support\Facades\Artisan;
use Modules\AdminPanel\Events\TenantCreated;

class MigrateTenantDatabase
{
    /**
     * Handle the event.
     *
     * @param TenantCreated $event
     * @return void
     */
    public function handle(TenantCreated $event)
    {
        Artisan::call('module:migrate', [
            'module'        => $event->tenant->type->name,
            '--force'       => true,
            '--database'    => 'tenant'
        ]);
    }
}
