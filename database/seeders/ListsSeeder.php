<?php

namespace Database\Seeders;

use App\Models\ConnectionType;
use App\Models\Currency;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\LimitCondition;
use App\Models\Pumps\Pump;
use App\Models\Pumps\PumpRegulation;
use App\Models\Pumps\PumpApplication;
use App\Models\Pumps\PumpCategory;
use App\Models\Pumps\PumpType;
use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpSeries;
use App\Models\Selections\PumpSelectionType;
use App\Models\Users\Area;
use App\Models\Users\Business;
use App\Models\Users\City;
use App\Models\Users\Role;
use Illuminate\Database\Seeder;

class ListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Currencies
         */
        Currency::create(['name' => 'RUB']);
        Currency::create(['name' => 'EUR']);
        Currency::create(['name' => 'USD']);

        /**
         * Businesses
         */
        Business::create(['id' => 1, 'name' => [
            'ru' => "Проектная организация",
            'en' => 'Project organization'
        ]]);
        Business::create(['id' => 2, 'name' => [
            'ru' => "Монтажная организация",
            'en' => 'Installation organization'
        ]]);
        Business::create(['id' => 3, 'name' => [
            'ru' => "Оптовый продавец",
            'en' => 'Wholesale seller'
        ]]);
        Business::create(['id' => 4, 'name' => [
            'ru' => "Розничный продавец",
            'en' => 'Retailer'
        ]]);
        Business::create(['id' => 5, 'name' => [
            'ru' => "Эксплуатирующая организация",
            'en' => 'Operating organization'
        ]]);
        Business::create(['id' => 6, 'name' => [
            'ru' => "Заказчик/застройщик",
            'en' => 'Customer/builder'
        ]]);

        /**
         * Areas
         */
        Area::create(['id' => 32, 'name' => [
            'ru' => 'Брянская область',
            'en' => 'Bryanskaya obl'
        ]]);
        Area::create(['id' => 40, 'name' => [
            'ru' => 'Орловская область',
            'en' => 'Orlovskaya obl'
        ]]);
        Area::create(['id' => 57, 'name' => [
            'ru' => 'Калужская область',
            'en' => 'Kalugskaya obl'
        ]]);

        /**
         * Cities
         */
        City::create(['name' => [
            'ru' => "Брянск",
            'en' => 'Bryansk'
        ], 'area_id' => 32]);

        City::create(['name' => [
            'ru' => "Орел",
            'en' => 'Orel'
        ], 'area_id' => 40]);

        City::create(['name' => [
            'ru' => "Калуга",
            'en' => 'Kaluga'
        ], 'area_id' => 57]);

        /**
         * Roles
         */
        Role::create(['id' => 1, 'name' => [
            'ru' => 'Администратор',
            'en' => 'Admin'
        ]]);
        Role::create(['id' => 2, 'name' => [
            'ru' => 'Пользователь',
            'en' => 'User'
        ]]);

        /**
         * Pump selection types
         */
        PumpSelectionType::create(['name' => 'Подбор по параметрам']);
        PumpSelectionType::create(['name' => 'Подбор аналога по марке']);

        /**
         * Pump applications
         */
        PumpApplication::create(['name' => [
            'ru' => "Водозабор",
            'en' => 'Water intake'
        ]]);
        PumpApplication::create(['name' => [
            'ru' => "Водоснабжение",
            'en' => 'Water supply'
        ]]);
        PumpApplication::create(['name' => [
            'ru' => "Горячее водоснабжение",
            'en' => 'Hot water supply'
        ]]);
        PumpApplication::create(['name' => [
            'ru' => "Дождевая вода",
            'en' => 'Rainwater'
        ]]);
        PumpApplication::create(['name' => [
            'ru' => "Кондиционирование/охлаждение",
            'en' => 'Air-conditioning/cooling'
        ]]);
        PumpApplication::create(['name' => [
            'ru' => "Отопление",
            'en' => 'Heating'
        ]]);
        PumpApplication::create(['name' => [
            'ru' => "Повышение давления",
            'en' => 'Increasing the pressure'
        ]]);

        /**
         * Pump regulations
         */
        PumpRegulation::create(['name' => [
            'ru' => "Нет",
            'en' => 'No'
        ]]);
        PumpRegulation::create(['name' => [
            'ru' => "Есть",
            'en' => 'Yes'
        ]]);

        /**
         * Pump categories
         */
        PumpCategory::create(['name' => [
            'ru' => "Одинарный",
            'en' => 'Single'
        ]]);
        PumpCategory::create(['name' => [
            'ru' => "Сдвоенный",
            'en' => 'Double'
        ]]);

        /**
         * Current phases
         */
        CurrentPhase::create(['value' => 1, 'voltage' => 220]);
        CurrentPhase::create(['value' => 3, 'voltage' => 380]);

        /**
         * DNs
         */
        DN::create(['value' => 15]);
        DN::create(['value' => 20]);
        DN::create(['value' => 25]);
        DN::create(['value' => 32]);
        DN::create(['value' => 40]);
        DN::create(['value' => 50]);
        DN::create(['value' => 65]);
        DN::create(['value' => 80]);
        DN::create(['value' => 100]);
        DN::create(['value' => 125]);
        DN::create(['value' => 150]);
        DN::create(['value' => 200]);
        DN::create(['value' => 250]);
        DN::create(['value' => 300]);

        /**
         * Pump filter types
         */
        PumpType::create(['name' => [
            'ru' => 'Инлайн',
            'en' => 'Inline'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Консольно-моноблочный',
            'en' => 'Console monoblock'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Консольный',
            'en' => 'Console'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Мокрый ротор',
            'en' => 'Wet rotor'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Сухой ротор',
            'en' => 'Dry rotor'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Многоступенчатый',
            'en' => 'Multi-stage'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Центробежный',
            'en' => 'Centrifugal'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Самовсасывающий',
            'en' => 'Self-priming'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Погружной',
            'en' => 'Submersible'
        ]]);
        PumpType::create(['name' => [
            'ru' => 'Дренажный',
            'en' => 'Drainage'
        ]]);

        /**
         * Limit conditions
         */
        LimitCondition::create(['value' => '=']);
        LimitCondition::create(['value' => '>=']);
        LimitCondition::create(['value' => '<=']);

        /**
         * Connection types
         */
        ConnectionType::create(['name' => [
            'ru' => 'Резьбовой',
            'en' => 'Threaded'
        ]]);
        ConnectionType::create(['name' => [
            'ru' => 'Фланцевый',
            'en' => 'Flanged'
        ]]);

        /**
         * Pump producers
         */
        PumpProducer::create(['id' => 1, 'name' => 'Wilo']);
        PumpProducer::create(['id' => 2, 'name' => 'Grundfos']);

        /**
         * Pump series
         */
        // WILO
        PumpSeries::create(['name' => 'MHI', 'producer_id' => 1]);
        PumpSeries::create(['name' => 'BL', 'producer_id' => 1]);
        PumpSeries::create(['name' => 'TOP-S', 'producer_id' => 1]);
        PumpSeries::create(['name' => 'TOP-Z', 'producer_id' => 1]);
        PumpSeries::create(['name' => 'IL-E', 'producer_id' => 1]);
        PumpSeries::create(['name' => 'IPL', 'producer_id' => 1]);
        PumpSeries::create(['name' => 'IL', 'producer_id' => 1]);

        // GRUNDFOS
        PumpSeries::create(['name' => 'CM-A AVBE', 'producer_id' => 2]);
        PumpSeries::create(['name' => 'CR HQQE', 'producer_id' => 2]);
        PumpSeries::create(['name' => 'UPS', 'producer_id' => 2]);
    }
}
