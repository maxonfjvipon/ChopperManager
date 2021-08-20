<?php

namespace Database\Seeders;

use App\Models\ConnectionType;
use App\Models\Currency;
use App\Models\CurrentPhase;
use App\Models\DN;
use App\Models\LimitCondition;
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
        Business::create(['id' => 1, 'name' => "Проектная организация"]);
        Business::create(['id' => 2, 'name' => "Монтажная организация"]);
        Business::create(['id' => 3, 'name' => "Оптовый продавец"]);
        Business::create(['id' => 4, 'name' => "Розничный продавец"]);
        Business::create(['id' => 5, 'name' => "Эксплуатирующая организация"]);
        Business::create(['id' => 6, 'name' => "Заказчик/застройщик"]);

        /**
         * Areas
         */
        Area::create(['id' => 32, 'name' => 'Брянская область']);
        Area::create(['id' => 40, 'name' => 'Орловская область']);
        Area::create(['id' => 57, 'name' => 'Калужская область']);

        /**
         * Cities
         */
        City::create(['name' => "Брянск", 'area_id' => 32]);
        City::create(['name' => 'Клинцы', 'area_id' => 32]);

        City::create(['name' => 'Орел', 'area_id' => 40]);
        City::create(['name' => 'Булава', 'area_id' => 40]);

        City::create(['name' => 'Калуга', 'area_id' => 57]);
        City::create(['name' => 'Болхов', 'area_id' => 57]);

        /**
         * Roles
         */
        Role::create(['id' => 1, 'name' => 'Администратор']);
        Role::create(['id' => 2, 'name' => 'Инфопользователь']);
        Role::create(['id' => 3, 'name' => 'Бизнеспользователь']);

        /**
         * Pump selection types
         */
        PumpSelectionType::create(['name' => 'Подбор по параметрам']);
        PumpSelectionType::create(['name' => 'Подбор аналога по марке']);

        /**
         * Pump applications
         */
        PumpApplication::create(['name' => "Водозабор"]);
        PumpApplication::create(['name' => "Водоснабжение"]);
        PumpApplication::create(['name' => "Горячее водоснабжение"]);
        PumpApplication::create(['name' => "Дождевая вода"]);
        PumpApplication::create(['name' => "Кондиционирование/охлаждение"]);
        PumpApplication::create(['name' => "Отопление"]);
        PumpApplication::create(['name' => "Повышение давления"]);

        /**
         * Pump regulations
         */
        PumpRegulation::create(['name' => "Нет"]);
        PumpRegulation::create(['name' => "Есть"]);

        /**
         * Pump categories
         */
        PumpCategory::create(['name' => "Одинарный"]);
        PumpCategory::create(['name' => "Сдвоенный"]);

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
        PumpType::create(['name' => 'Инлайн']);
        PumpType::create(['name' => 'Консольно-моноблочный']);
        PumpType::create(['name' => 'Консольный']);
        PumpType::create(['name' => 'Мокрый ротор']);
        PumpType::create(['name' => 'Сухой ротор']);
        PumpType::create(['name' => 'Многоступенчатый']);
        PumpType::create(['name' => 'Центробежный']);
        PumpType::create(['name' => 'Самовсасывающий']);
        PumpType::create(['name' => 'Погружной']);
        PumpType::create(['name' => 'Дренажный']);

        /**
         * Limit conditions
         */
        LimitCondition::create(['value' => '=']);
        LimitCondition::create(['value' => '>=']);
        LimitCondition::create(['value' => '<=']);

        /**
         * Connection types
         */
        ConnectionType::create(['name' => 'Резьбовой']);
        ConnectionType::create(['name' => 'Фланцевый']);

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
