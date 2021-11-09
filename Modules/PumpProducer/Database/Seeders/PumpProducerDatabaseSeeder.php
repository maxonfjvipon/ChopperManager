<?php

namespace Modules\PumpProducer\Database\Seeders;

use Database\Seeders\AdminSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PumpProducerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('module:seed', [
            'module' => 'Core',
            '--force' => true,
            '--database' => 'tenant',
        ]);
        (new AdminSeeder())->run();
    }
}
