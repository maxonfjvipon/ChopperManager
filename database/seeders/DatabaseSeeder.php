<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Selection\Entities\StationType;
use Modules\User\Entities\User;
use Modules\User\Entities\UserRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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

        /** Control system types */
        DB::insert("INSERT INTO `control_system_types` (`id`, `name`, `description`, `station_type`) VALUES
            (1, 'DD', 'прямой пуск'," . StationType::Fire . "),
            (2, 'SD', 'пуск звезда-треугольник'," . StationType::Fire . "),
            (3, 'SS', 'плавный пуск'," . StationType::Fire . "),
            (4, 'VF', 'частотное управления двигателями основных и резервных насосов'," . StationType::Fire . "),
            (5, 'Comfort', null," . StationType::Water . "),
            (6, 'Multi', null," . StationType::Water . "),
            (7, 'Multi-E', null," . StationType::Water . "),
            (8, 'Multi-EL', null," . StationType::Water . "),
            (9, 'WS-AF', null," . StationType::Water . "),
            (10, 'ШУПН', null, null),
            (11, 'ШУПН с жокеем', null, null);");


        /** Users */
        $max = User::create([
            'first_name' => 'Max',
            'middle_name' => 'Trunnikov',
            'email' => 'maxonfjvipon@admin.com',
            'password' => Hash::make('maximtrun19'),
            'itn' => '00000000001',
            'area_id' => 6,
            'role' => UserRole::Admin,
            'verified_at' => now()
        ]);
    }
}
