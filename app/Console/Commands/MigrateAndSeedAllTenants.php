<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateAndSeedAllTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants_migrate_and_seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate and seed all tenants';

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
        Artisan::call('migrate_tenants_from_core');
        Artisan::call('seed_all_tenants');
    }
}
