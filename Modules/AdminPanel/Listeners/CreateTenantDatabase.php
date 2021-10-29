<?php

namespace Modules\AdminPanel\Listeners;

use Exception;
use Modules\AdminPanel\Events\TenantCreated;

class CreateTenantDatabase extends TenantDatabaseListener
{
    /**
     * Handle the event.
     *
     * @param TenantCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(TenantCreated $event)
    {
        if (!$this->dbManager->createDatabase($event->tenant->database)) {
            throw new Exception('Произошла ошибка при создании базы данных');
        }
    }
}
