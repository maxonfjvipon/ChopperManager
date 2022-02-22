<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DB::table('model_has_roles')->update([
            'model_type' => User::class
        ]);
        DB::table('model_has_permissions')->update([
            'model_type' => User::class
        ]);
        $imgPath = "/img/selections-dashboard/";
        SelectionType::updateOrCreate(['id' => 1], ['name' => [
            'en' => 'Single pump selection',
            'ru' => 'Подбор одинарного насоса'
        ], 'pumpable_type' => Pump::$SINGLE_PUMP, 'img' => $imgPath . '01.png']);
        SelectionType::updateOrCreate(['id' => 2], ['name' => [
            'en' => 'Double pump selection',
            'ru' => 'Подбор сдвоенного насоса'
        ], 'pumpable_type' => Pump::$DOUBLE_PUMP, 'img' => $imgPath . '02.png']);
        SelectionType::updateOrCreate(['id' => 3], ['name' => [
            'en' => 'Water supply pumping station selection',
            'ru' => 'Подбор станции водоснбажения'
        ], 'pumpable_type' => Pump::$STATION_WATER, 'img' => $imgPath . '05.png']);
        SelectionType::updateOrCreate(['id' => 4], ['name' => [
            'en' => 'Fire extinguishing pumping station selection',
            'ru' => 'Подбор станции пожаротушения'
        ], 'pumpable_type' => Pump::$STATION_FIRE, 'img' => $imgPath . '06.png']);
    }
}
