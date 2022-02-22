<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateForTenantsFromCoreModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate_tenants_from_core';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all migrations for all tenant from Project module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call("tenants:artisan \"migrate --database=tenant --path=Modules/Project/Database/Migrations\"");
    }
}
