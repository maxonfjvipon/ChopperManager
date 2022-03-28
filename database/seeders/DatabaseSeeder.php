<?php

namespace Database\Seeders;

use App\Models\CollectorType;
use App\Models\ConnectionType;
use App\Models\Currency;
use App\Models\DN;
use App\Models\MontageType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $models = ['user', 'project', 'pump', 'selection', 'brand', 'series', 'components', 'organization'];
        $actions = ['access', 'create', 'show', 'edit', 'delete', 'restore'];
        $permissions = [];
        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissions[] = $model . '_' . $action;
            }
        }
        $permissions[] = 'pump_import';
        $permissions[] = 'series_import';
        $permissions[] = 'components_import';
        $permissions[] = 'selection_export';

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        /** * ROLES AND PERMISSIONS */
        $landlordRole = Role::create(['name' => 'SuperAdmin']); // check AuthServiceProvider
        foreach ($permissions as $permission) {
            $landlordRole->givePermissionTo($permission);
        }

        /** ADMIN ROLE */
        $adminRole = Role::create(['name' => 'Admin']);
        $adminPermissions = [
            'user_access',
            'user_create',
            'user_show',
            'user_edit',
            'user_delete',
            'user_restore',

            'project_access',
            'project_create',
            'project_show',
            'project_edit',
            'project_delete',
            'project_restore',

            'pump_access',
            'pump_create',
            'pump_show',
            'pump_edit',
            'pump_delete',
            'pump_restore',

            'selection_access',
            'selection_create',
            'selection_show',
            'selection_edit',
            'selection_delete',
            'selection_restore',
            'selection_export',

            'brand_access',
            'brand_create',
            'brand_show',
            'brand_edit',
            'brand_delete',
            'brand_restore',

            'series_access',
            'series_create',
            'series_show',
            'series_edit',
            'series_delete',
            'series_restore',

            'components_access',
            'components_create',
            'components_show',
            'components_edit',
            'components_delete',
            'components_restore',

            'organization_access',
            'organization_create',
            'organization_show',
            'organization_edit',
            'organization_delete',
            'organization_restore',
        ];

        foreach ($adminPermissions as $permission) {
            $adminRole->givePermissionTo($permission);
        }

        /** CLIENT ROLE */
        $clientRole = Role::create(['name' => 'Client']);
        $clientPermissions = [
            'project_access',
            'project_create',
            'project_show',
            'project_edit',
            'project_delete',
            'project_restore',

            'pump_access',
            'pump_show',

            'selection_access',
            'selection_create',
            'selection_show',
            'selection_edit',
            'selection_delete',
            'selection_restore',
//            'selection_export',
        ];
        foreach ($clientPermissions as $permission) {
            $clientRole->givePermissionTo($permission);
        }

        /** * Connection types */
        ConnectionType::create(['id' => 1, 'name' => 'Резьбовой']);
        ConnectionType::create(['id' => 2, 'name' => 'Фланцевый']);

        /** * DNs */
        DN::create(['id' => 1, 'value' => 15]);
        DN::create(['id' => 2, 'value' => 20]);
        DN::create(['id' => 3, 'value' => 25]);
        DN::create(['id' => 4, 'value' => 32]);
        DN::create(['id' => 6, 'value' => 40]);
        DN::create(['id' => 7, 'value' => 50]);
        DN::create(['id' => 8, 'value' => 65]);
        DN::create(['id' => 9, 'value' => 80]);
        DN::create(['id' => 10, 'value' => 100]);
        DN::create(['id' => 11, 'value' => 125]);
        DN::create(['id' => 12, 'value' => 150]);
        DN::create(['id' => 13, 'value' => 200]);
        DN::create(['id' => 14, 'value' => 250]);
        DN::create(['id' => 15, 'value' => 300]);

        /** * Areas */
        DB::insert("INSERT INTO `areas` (`id`, `name`) VALUES
            (1, 'Алтайский край'),
            (2, 'Амурская область'),
            (3, 'Архангельская область'),
            (4, 'Астраханская область'),
            (5, 'Белгородская область'),
            (6, 'Брянская область'),
            (7, 'Владимирская область'),
            (8, 'Волгоградская область'),
            (9, 'Вологодская область'),
            (10, 'Воронежская область'),
            (11, 'г. Москва'),
            (12, 'Еврейская автономная область'),
            (13, 'Забайкальский край'),
            (14, 'Ивановская область'),
            (15, 'Иные территории, включая город и космодром Байконур'),
            (16, 'Иркутская область'),
            (17, 'Кабардино-Балкарская Республика'),
            (18, 'Калининградская область'),
            (19, 'Калужская область'),
            (20, 'Камчатский край'),
            (21, 'Карачаево-Черкесская Республика'),
            (22, 'Кемеровская область - Кузбасс'),
            (23, 'Кировская область'),
            (24, 'Костромская область'),
            (25, 'Краснодарский край'),
            (26, 'Красноярский край'),
            (27, 'Курганская область'),
            (28, 'Курская область'),
            (29, 'Ленинградская область'),
            (30, 'Липецкая область'),
            (31, 'Магаданская область'),
            (32, 'Московская область'),
            (33, 'Мурманская область'),
            (34, 'Ненецкий автономный округ'),
            (35, 'Нижегородская область'),
            (36, 'Новгородская область'),
            (37, 'Новосибирская область'),
            (38, 'Омская область'),
            (39, 'Оренбургская область'),
            (40, 'Орловская область'),
            (41, 'Пензенская область'),
            (42, 'Пермский край'),
            (43, 'Приморский край'),
            (44, 'Псковская область'),
            (45, 'Республика Адыгея (Адыгея)'),
            (46, 'Республика Алтай'),
            (47, 'Республика Башкортостан'),
            (48, 'Республика Бурятия'),
            (49, 'Республика Дагестан'),
            (50, 'Республика Ингушетия'),
            (51, 'Республика Калмыкия'),
            (52, 'Республика Карелия'),
            (53, 'Республика Коми'),
            (54, 'Республика Крым'),
            (55, 'Республика Марий Эл'),
            (56, 'Республика Мордовия'),
            (57, 'Республика Саха (Якутия)'),
            (58, 'Республика Северная Осетия - Алания'),
            (59, 'Республика Татарстан (Татарстан)'),
            (60, 'Республика Тыва'),
            (61, 'Республика Хакасия'),
            (62, 'Ростовская область'),
            (63, 'Рязанская область'),
            (64, 'Самарская область'),
            (65, 'Санкт-Петербург'),
            (66, 'Саратовская область'),
            (67, 'Сахалинская область'),
            (68, 'Свердловская область'),
            (69, 'Севастополь'),
            (70, 'Смоленская область'),
            (71, 'Ставропольский край'),
            (72, 'Тамбовская область'),
            (73, 'Тверская область'),
            (74, 'Томская область'),
            (75, 'Тульская область'),
            (76, 'Тюменская область'),
            (77, 'Удмуртская Республика'),
            (78, 'Ульяновская область'),
            (79, 'Хабаровский край'),
            (80, 'Ханты-Мансийский автономный округ - Югра'),
            (81, 'Челябинская область'),
            (82, 'Чеченская Республика'),
            (83, 'Чувашская Республика - Чувашия'),
            (84, 'Чукотский автономный округ'),
            (85, 'Ямало-Ненецкий автономный округ'),
            (86, 'Ярославская область');
            ");

        /** Armature types*/
        DB::insert("INSERT INTO `armature_types` (`id`, `name`, `code`) VALUES
            (1, 'Быстросъемное соединение', 'AM'),
            (2, 'Затвор', 'ZF'),
            (3, 'Кран', 'KR'),
            (4, 'Ниппель', 'NR'),
            (5, 'Обратный клапан', 'OKF'),
            (6, 'Фланец с переходом на резьбу', 'FNR'),
            (7, 'Катушка', 'KatF'),
            (8, 'Катушка с патрубком', 'KatF+P'),
            (9, 'Комплект резьбового тройника', 'KRT');");

        /** *Collector materials */
        DB::insert("INSERT INTO `collector_materials` (`id`, `name`) VALUES
            (1, 'Сталь-20'),
            (2, 'AISI-304');");

        /** Collector types */
        CollectorType::create(['id' => 1, 'name' => 'Общий']);
        CollectorType::create(['id' => 2, 'name' => 'Раздельный']);

        /** Control system types */
        DB::insert("INSERT INTO `control_system_types` (`id`, `name`, `description`) VALUES
            (1, 'DD', 'прямой пуск'),
            (2, 'SD', 'пуск звезда-треугольник'),
            (3, 'SS', 'плавный пуск'),
            (4, 'VF', 'частотное управления двигателями основных и резервных насосов'),
            (5, 'Comfort', null),
            (6, 'Multi', null),
            (7, 'Multi-E', null),
            (8, 'Multi-EL', null),
            (9, 'WS-AF', null),
            (10, 'ШУПН', null),
            (11, 'ШУПН с жокеем', null);");

        /** Currencies */
        Currency::create(['id' => 1, 'code' => "RUB"]);
        Currency::create(['id' => 2, 'code' => "USD"]);
        Currency::create(['id' => 3, 'code' => "EUR"]);

        /** Montage types */
        MontageType::create(['id' => 1, 'name' => "Навесной"]);
        MontageType::create(['id' => 2, 'name' => "Отдельностоящий"]);

        /** Pump orientations */
        DB::insert("INSERT INTO `pump_orientations` (`id`, `name`) VALUES
            (1, 'Вертикальный'),
            (2, 'Горизонтальный');");

        /** Collector switches */
        DB::insert("INSERT INTO `collector_switches` (`id`, `name`, `code`) VALUES
            (1, 'Резьба', 'Trd'),
            (2, 'Овальный фланец', 'OvlFln'),
            (3, 'Фланец', 'Fln'),
            (4, 'Фланец на резьбу', 'FlnToTrd');");

        /** Project statuses */
        DB::insert("INSERT INTO `project_statuses` (`id`, `name`) VALUES
            (1, 'Проект'),
            (2, 'Цикл 0'),
            (3, 'Цикл крыша'),
            (4, 'Архив');");

        /** Station types */
        DB::insert("INSERT INTO `station_types` (`id`, `name`) VALUES
            (1, 'Водоснабжение'),
            (2, 'Пожаротушение');");

        /** Selection types */
        DB::insert("INSERT INTO `selection_types` (`id`, `name`) VALUES
            (1, 'Автоматический'),
            (2, 'Ручной');");

        /** Organizations */
        $bpe = Organization::create([
            'name' => 'ООО БПЕ',
            'itn' => '00000000001',
            'address' => 'ул. Литейная, 2',
            'area_id' => 6
        ]);

        /** Users */
        $max = User::create([
            'login' => 'maxonfjvipon',
            'password' => Hash::make('maximtrun19'),
            'email' => 'maxonfvjipon@admin.com',
            'first_name' => 'Max',
            'middle_name' => 'Trunnikov',
            'itn' => '00000000001',
            'area_id' => 6,
            'organization_id' => $bpe->id
        ]);

        $max->assignRole('SuperAdmin');
    }
}
