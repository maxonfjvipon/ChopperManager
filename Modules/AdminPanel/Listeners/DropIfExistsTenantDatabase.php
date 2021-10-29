<?php

namespace Modules\AdminPanel\Listeners;

use Exception;
use Modules\AdminPanel\Events\TenantCreated;

class DropIfExistsTenantDatabase extends TenantDatabaseListener
{
    public function handle(TenantCreated $event)
    {
        if (!$this->dbManager->dropDatabaseIfExists($event->tenant->database)) {
            throw new Exception('Произошла ошибка при удалении базы данных');
        };
    }
}
