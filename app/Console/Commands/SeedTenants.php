<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed_all_tenants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call seeder that seeds through all tenants';

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
        Artisan::call('db:seed', [
            '--class' => 'TenantsSeeder'
        ]);
    }
}
