<?php

namespace Modules\AdminPanel\Listeners;

use App\Support\DatabaseManager;
use JetBrains\PhpStorm\Pure;

class TenantDatabaseListener
{
    protected DatabaseManager $dbManager;

    /**
     * Create the event listener.
     *
     * @return void
     */
    #[Pure] public function __construct()
    {
        $this->dbManager = new DatabaseManager();
    }
}
