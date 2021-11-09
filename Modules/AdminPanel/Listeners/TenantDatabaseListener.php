<?php

namespace Modules\AdminPanel\Listeners;

use JetBrains\PhpStorm\Pure;
use Modules\AdminPanel\Support\DatabaseManager;

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
