<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Project\Entities\ProjectDeliveryStatus;
use Modules\Project\Entities\ProjectStatus;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\Business;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestingSeeder extends Seeder
{
    public function run()
    {
        try {
            /** * Permissions */
            $models = ['role', 'user', 'project', 'pump', 'selection', 'brand', 'series'];
            $actions = ['access', 'create', 'show', 'edit', 'delete', 'restore', 'export'];
            $permissions = [];
            foreach ($models as $model) {
                foreach ($actions as $action) {
                    $permissions[] = $model . '_' . $action;
                }
            }
            $permissions[] = 'price_list_import';
            $permissions[] = 'pump_import';
            $permissions[] = 'pump_import_media';
            $permissions[] = 'selection_create_without_saving';
            $permissions[] = 'series_import';
            $permissions[] = 'series_import_media';
            $permissions[] = 'project_clone';
            $permissions[] = 'project_statistics';
            $permissions[] = 'user_statistics';

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
                'role_access',
                'role_create',
                'role_show',
                'role_edit',
                'role_delete',
                'role_restore',

                'user_access',
                'user_create',
                'user_show',
                'user_edit',
                'user_statistics',

                'project_access',
                'project_create',
                'project_show',
                'project_edit',
                'project_delete',
                'project_restore',
                'project_export',
                'project_clone',
                'project_statistics',

                'pump_access',
                'pump_create',
                'pump_show',
                'pump_edit',
                'pump_delete',
                'pump_restore',
                'pump_import',
                'pump_import_media',
                'price_list_import',

                'selection_access',
                'selection_create',
                'selection_create_without_saving',
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
                'series_import',
                'series_import_media'
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
                'project_export',
                'project_clone',

                'pump_access',
                'pump_show',

                'selection_access',
                'selection_create',
                'selection_create_without_saving',
                'selection_show',
                'selection_edit',
                'selection_delete',
                'selection_restore',
                'selection_export',
            ];
            foreach ($clientPermissions as $permission) {
                $clientRole->givePermissionTo($permission);
            }

            /** * Businesses */
            Business::create(['id' => 1, 'name' => ['ru' => "Проектная организация", 'en' => 'Project organization']]);
            Business::create(['id' => 2, 'name' => ['ru' => "Монтажная организация", 'en' => 'Installation organization']]);
            Business::create(['id' => 3, 'name' => ['ru' => "Оптовый продавец", 'en' => 'Wholesale seller']]);
            Business::create(['id' => 4, 'name' => ['ru' => "Розничный продавец", 'en' => 'Retailer']]);
            Business::create(['id' => 5, 'name' => ['ru' => "Эксплуатирующая организация", 'en' => 'Operating organization']]);
            Business::create(['id' => 6, 'name' => ['ru' => "Заказчик/застройщик", 'en' => 'Customer/builder']]);

            /** * Pump applications */
            PumpApplication::create(['id' => 1, 'name' => ['ru' => "Водозабор", 'en' => 'Water intake']]);
            PumpApplication::create(['id' => 2, 'name' => ['ru' => "Водоснабжение", 'en' => 'Water supply']]);
            PumpApplication::create(['id' => 3, 'name' => ['ru' => "Горячее водоснабжение", 'en' => 'Hot water supply']]);
            PumpApplication::create(['id' => 4, 'name' => ['ru' => "Дождевая вода", 'en' => 'Rain water']]);
            PumpApplication::create(['id' => 5, 'name' => ['ru' => "Кондиционирование/охлаждение", 'en' => 'Air-conditioning/cooling']]);
            PumpApplication::create(['id' => 6, 'name' => ['ru' => "Отопление", 'en' => 'Heating']]);
            PumpApplication::create(['id' => 7, 'name' => ['ru' => "Повышение давления", 'en' => 'Increasing the pressure']]);

            /** * Pump regulations */
            ElPowerAdjustment::create(['id' => 1, 'name' => ['ru' => "Да", 'en' => 'Yes']]);
            ElPowerAdjustment::create(['id' => 2, 'name' => ['ru' => "Нет", 'en' => 'No']]);

            /** * Pump categories */
            PumpCategory::create(['id' => 1, 'name' => ['ru' => "Одинарный", 'en' => 'Single']]);
            PumpCategory::create(['id' => 2, 'name' => ['ru' => "Сдвоенный", 'en' => 'Double']]);

            /** * Mains phases */
            MainsConnection::create(['id' => 1, 'phase' => 1, 'voltage' => 220]);
            MainsConnection::create(['id' => 3, 'phase' => 3, 'voltage' => 380]);

            /** * DNs */
            DN::create(['id' => 1, 'value' => 15]);
            DN::create(['id' => 2, 'value' => 20]);
            DN::create(['id' => 3, 'value' => 25]);
            DN::create(['id' => 4, 'value' => 32]);
            DN::create(['id' => 5, 'value' => 40]);
            DN::create(['id' => 6, 'value' => 50]);
            DN::create(['id' => 7, 'value' => 65]);
            DN::create(['id' => 8, 'value' => 80]);
            DN::create(['id' => 9, 'value' => 100]);
            DN::create(['id' => 10, 'value' => 125]);
            DN::create(['id' => 11, 'value' => 150]);
            DN::create(['id' => 12, 'value' => 200]);
            DN::create(['id' => 13, 'value' => 250]);
            DN::create(['id' => 14, 'value' => 300]);

            /** * Pump types */
            PumpType::create(['id' => 1, 'name' => ['ru' => 'Ин-лайн', 'en' => 'In-line']]);
            PumpType::create(['id' => 2, 'name' => ['ru' => 'Консольно-моноблочный', 'en' => 'Console monoblock']]);
            PumpType::create(['id' => 3, 'name' => ['ru' => 'Консольный', 'en' => 'Console']]);
            PumpType::create(['id' => 4, 'name' => ['ru' => 'Мокрый ротор', 'en' => 'Wet rotor']]);
            PumpType::create(['id' => 5, 'name' => ['ru' => 'Сухой ротор', 'en' => 'Dry rotor']]);
            PumpType::create(['id' => 6, 'name' => ['ru' => 'Многоступенчатый', 'en' => 'Multi-stage']]);
            PumpType::create(['id' => 7, 'name' => ['ru' => 'Центробежный', 'en' => 'Centrifugal']]);
            PumpType::create(['id' => 8, 'name' => ['ru' => 'Самовсасывающий', 'en' => 'Self-priming']]);
            PumpType::create(['id' => 9, 'name' => ['ru' => 'Погружной', 'en' => 'Submersible']]);
            PumpType::create(['id' => 10, 'name' => ['ru' => 'Дренажный', 'en' => 'Drainage']]);
            PumpType::create(['id' => 11, 'name' => ['ru' => 'Одноступенчатый', 'en' => 'Single-stage']]);

            /** * Limit conditions */
            LimitCondition::create(['id' => 1, 'value' => '=']);
            LimitCondition::create(['id' => 2, 'value' => '>=']);
            LimitCondition::create(['id' => 3, 'value' => '<=']);

            /** * Connection types */
            ConnectionType::create(['id' => 1, 'name' => ['ru' => 'Резьбовой', 'en' => 'Threaded']]);
            ConnectionType::create(['id' => 2, 'name' => ['ru' => 'Фланцевый', 'en' => 'Flanged']]);

            /** * Selection ranges */
            SelectionRange::create(['id' => 1, 'name' => ['en' => '1/3', 'ru' => '1/3'], 'value' => 0.33]);
            SelectionRange::create(['id' => 2, 'name' => ['en' => '3/5', 'ru' => '3/5'], 'value' => 0.2]);
            SelectionRange::create(['id' => 3, 'name' => ['en' => 'Custom', 'ru' => 'Произв.'], 'value' => null]);

            /** Selection types*/
            $imgPath = "/img/selections-dashboard/";
            SelectionType::create(['id' => 1, 'name' => [
                'en' => 'Single pump selection',
                'ru' => 'Подбор одинарного насоса'
            ], 'pumpable_type' => Pump::$SINGLE_PUMP, 'img' => $imgPath . '01.png']);
            SelectionType::create(['id' => 2, 'name' => [
                'en' => 'Double pump selection',
                'ru' => 'Подбор сдвоенного насоса'
            ], 'pumpable_type' => Pump::$DOUBLE_PUMP, 'img' => $imgPath . '02.png']);
            SelectionType::create(['id' => 3, 'name' => [
                'en' => 'Water supply pumping station selection',
                'ru' => 'Подбор станции водоснбажения'
            ], 'pumpable_type' => Pump::$STATION_WATER, 'img' => $imgPath . '05.png']);
            SelectionType::create(['id' => 4, 'name' => [
                'en' => 'Fire extinguishing pumping station selection',
                'ru' => 'Подбор станции пожаротушения'
            ], 'pumpable_type' => Pump::$STATION_FIRE, 'img' => $imgPath . '06.png']);

            /** * Double pump work schemes */
            DoublePumpWorkScheme::create(['id' => 1, 'name' => ['en' => 'Main standby', 'ru' => 'Рабочий-резервный']]);
            DoublePumpWorkScheme::create(['id' => 2, 'name' => ['en' => 'Main peak', 'ru' => 'Рабочий-пиковый']]);

            /** * Project statuses */
            ProjectStatus::create(['id' => 1, 'name' => ['ru' => "Новый", 'en' => 'New']]);
            ProjectStatus::create(['id' => 2, 'name' => ['ru' => "В работе", 'en' => 'In progress']]);
            ProjectStatus::create(['id' => 3, 'name' => ['ru' => "Архивный", 'en' => 'Archived']]);
            ProjectStatus::create(['id' => 4, 'name' => ['ru' => "Удаленный", 'en' => 'Deleted']]);

            ProjectDeliveryStatus::create(['id' => 1, 'name' => ['ru' => 'Не поставлен', 'en' => 'Not delivered']]);
            ProjectDeliveryStatus::create(['id' => 2, 'name' => ['ru' => 'Поставлен', 'en' => 'Delivered']]);

            /** * Currencies */
            DB::insert("INSERT INTO currencies (id, code, name, symbol) VALUES
            (1, 'EUR', 'Euro', '€'),
            (2, 'AED', 'Dirham', '.د.ب'),
            (3, 'AFN', 'Afghani', '؋'),
            (4, 'XCD', 'Dollar', 'EC$'),
            (5, 'ALL', 'Lek', 'lek'),
            (6, 'AMD', 'Dram', '֏'),
            (7, 'AOA', 'Kwanza', 'Kz'),
            (8, 'ARS', 'Peso', '$'),
            (9, 'USD', 'Dollar', '$'),
            (10, 'AUD', 'Dollar', '$'),
            (11, 'AWG', 'Guilder', 'ƒ'),
            (12, 'AZN', 'Manat', 'ман'),
            (13, 'BAM', 'Marka', 'KM'),
            (14, 'BBD', 'Dollar', '$'),
            (15, 'BDT', 'Taka', 'Tk'),
            (16, 'XOF', 'Franc', 'CFA'),
            (17, 'BGN', 'Lev', 'лв'),
            (18, 'BHD', 'Dinar', 'BD'),
            (19, 'BIF', 'Franc', 'BIF'),
            (20, 'BMD', 'Dollar', '$'),
            (21, 'BND', 'Dollar', '$'),
            (22, 'BOB', 'Boliviano', '\$b'),
            (23, 'BRL', 'Real', 'R$'),
            (24, 'BSD', 'Dollar', 'B$'),
            (25, 'BTN', 'Ngultrum', 'Nu.'),
            (26, 'NOK', 'Krone', 'kr'),
            (27, 'BWP', 'Pula', 'P'),
            (28, 'BYR', 'Ruble', 'р'),
            (29, 'BZD', 'Dollar', 'BZ$'),
            (30, 'CAD', 'Dollar', '$'),
            (31, 'CDF', 'Franc', 'CDF'),
            (32, 'XAF', 'Franc', 'XAF'),
            (33, 'CHF', 'Franc', 'CHF'),
            (34, 'NZD', 'Dollar', '$'),
            (35, 'CLP', 'Peso', '$'),
            (36, 'CNY', 'Yuan Renminbi', '¥'),
            (37, 'COP', 'Peso', '$'),
            (38, 'CRC', 'Colon', '₡'),
            (39, 'CUP', 'Peso', '₱'),
            (40, 'CVE', 'Escudo', '$'),
            (41, 'ANG', 'Guilder', 'ƒ'),
            (42, 'CZK', 'Koruna', 'Kč'),
            (43, 'DJF', 'Franc', 'fdj'),
            (44, 'DKK', 'Krone', 'kr'),
            (45, 'DOP', 'Peso', '$'),
            (46, 'DZD', 'Dinar', 'جد'),
            (47, 'EGP', 'Pound', '£ '),
            (48, 'MAD', 'Dirham', 'م.د.'),
            (49, 'ERN', 'Nakfa', 'ናቕፋ'),
            (50, 'ETB', 'Birr', 'Br'),
            (51, 'FJD', 'Dollar', '$'),
            (52, 'FKP', 'Pound', '£'),
            (53, 'GBP', 'Pound', '£'),
            (54, 'GEL', 'Lari', 'ლ'),
            (55, 'GHS', 'Cedi', 'GH¢'),
            (56, 'GIP', 'Pound', '£'),
            (57, 'GMD', 'Dalasi', 'D'),
            (58, 'GNF', 'Franc', 'GNF'),
            (59, 'GTQ', 'Quetzal', 'Q'),
            (60, 'GYD', 'Dollar', '$'),
            (61, 'HKD', 'Dollar', 'HK$'),
            (62, 'HNL', 'Lempira', 'L'),
            (63, 'HRK', 'Kuna', 'kn'),
            (64, 'HTG', 'Gourde', 'G'),
            (65, 'HUF', 'Forint', 'Ft'),
            (66, 'IDR', 'Rupiah', 'Rp'),
            (67, 'ILS', 'Shekel', '₪'),
            (68, 'INR', 'Rupee', '₹'),
            (69, 'IQD', 'Dinar', 'ع.د'),
            (70, 'IRR', 'Rial', '﷼'),
            (71, 'ISK', 'Krona', 'kr'),
            (72, 'JMD', 'Dollar', 'J$'),
            (73, 'JOD', 'Dinar', 'JD'),
            (74, 'JPY', 'Yen', '¥'),
            (75, 'KES', 'Shilling', 'KSh'),
            (76, 'KGS', 'Som', 'лв'),
            (77, 'KHR', 'Riels', '៛'),
            (78, 'KMF', 'Franc', 'KMF'),
            (79, 'KPW', 'Won', '₩'),
            (80, 'KRW', 'Won', '₩'),
            (81, 'KWD', 'Dinar', 'ك'),
            (82, 'KYD', 'Dollar', '$'),
            (83, 'KZT', 'Tenge', '₸'),
            (84, 'LAK', 'Kip', '₭'),
            (85, 'LBP', 'Pound', 'ل.ل'),
            (86, 'LKR', 'Rupee', 'Rs'),
            (87, 'LRD', 'Dollar', '$'),
            (88, 'LSL', 'Loti', 'L or M'),
            (89, 'LTL', 'Litas', 'Lt'),
            (90, 'LVL', 'Lat', 'Ls'),
            (91, 'LYD', 'Dinar', ' د.ل'),
            (92, 'MDL', 'Leu', 'L'),
            (93, 'MGA', 'Ariary', 'Ar'),
            (94, 'MKD', 'Denar', 'ден'),
            (95, 'MMK', 'Kyat', 'K'),
            (96, 'MNT', 'Tugrik', '₮'),
            (97, 'MOP', 'Pataca', 'MOP$'),
            (98, 'MRO', 'Ouguiya', 'UM'),
            (99, 'MUR', 'Rupee', 'Rs'),
            (100, 'MVR', 'Rufiyaa', 'rf'),
            (101, 'MWK', 'Kwacha', 'MK'),
            (102, 'MXN', 'Peso', '$'),
            (103, 'MYR', 'Ringgit', 'RM'),
            (104, 'MZN', 'Metical', 'MT'),
            (105, 'NAD', 'Dollar', '$'),
            (106, 'XPF', 'Franc', 'XPF'),
            (107, 'NGN', 'Naira', '₦'),
            (108, 'NIO', 'Cordoba', 'C$'),
            (109, 'NPR', 'Rupee', 'Rs'),
            (110, 'OMR', 'Rial', 'ع.ر.'),
            (111, 'PAB', 'Balboa', 'B/'),
            (112, 'PEN', 'Sol', 'S/'),
            (113, 'PGK', 'Kina', 'K'),
            (114, 'PHP', 'Peso', '₱'),
            (115, 'PKR', 'Rupee', 'Rs'),
            (116, 'PLN', 'Zloty', 'zł'),
            (117, 'PYG', 'Guarani', '₲'),
            (118, 'QAR', 'Rial', 'ق.ر '),
            (119, 'RON', 'Leu', 'lei'),
            (120, 'RSD', 'Dinar', 'РСД'),
            (121, 'RUB', 'Ruble', '₽'),
            (122, 'RWF', 'Franc', 'R₣'),
            (123, 'SAR', 'Rial', 'ر.س'),
            (124, 'SBD', 'Dollar', 'SI$'),
            (125, 'SCR', 'Rupee', 'Rs'),
            (126, 'SDG', 'Pound', '£'),
            (127, 'SSP', 'Pound', '£'),
            (128, 'SEK', 'Krona', 'kr'),
            (129, 'SGD', 'Dollar', '$'),
            (130, 'SHP', 'Pound', '£'),
            (131, 'SLL', 'Leone', 'Le'),
            (132, 'SOS', 'Shilling', 'S'),
            (133, 'SRD', 'Dollar', '$'),
            (134, 'STD', 'Dobra', 'Db'),
            (135, 'SYP', 'Pound', '£'),
            (136, 'SZL', 'Lilangeni', 'L'),
            (137, 'THB', 'Baht', '฿'),
            (138, 'TJS', 'Somoni', 'смн.'),
            (139, 'TMT', 'Manat', 'T'),
            (140, 'TND', 'Dinar', 'din'),
            (141, 'TOP', 'Pa''anga', 'T$'),
            (142, 'TRY', 'Lira', '₺'),
            (143, 'TTD', 'Dollar', 'TT$'),
            (144, 'TWD', 'Dollar', 'NT$'),
            (145, 'TZS', 'Shilling', 'Sh'),
            (146, 'UAH', 'Hryvnia', '₴'),
            (147, 'UGX', 'Shilling', 'USh'),
            (148, 'UYU', 'Peso', '\$U'),
            (149, 'UZS', 'Som', 'лв'),
            (150, 'VEF', 'Bolivar', 'Bs'),
            (151, 'VND', 'Dong', '₫'),
            (152, 'VUV', 'Vatu', 'VT'),
            (153, 'WST', 'Tala', '$'),
            (154, 'YER', 'Rial', '﷼'),
            (155, 'ZAR', 'Rand', 'R'),
            (156, 'ZMK', 'Kwacha', 'K'),
            (157, 'ZWL', 'Dollar', '$');");

            DB::insert("INSERT INTO `countries` (`id`, `name`, `currency_id`, `code`) VALUES
        (1, '{\"be\": \"Расея\", \"de\": \"Russland\", \"en\": \"Russia\", \"es\": \"Rusia\", \"fr\": \"Russie\", \"it\": \"Russia\", \"ja\": \"ロシア\", \"lt\": \"Rusija\", \"lv\": \"Krievija\", \"pl\": \"Rosja\", \"pt\": \"Rússia\", \"ru\": \"Россия\", \"uk\": \"Росiя\"}', 121, 'RUS'),
        (2, '{\"be\": \"Украіна\", \"de\": \"Ukraine\", \"en\": \"Ukraine\", \"es\": \"Ucrania\", \"fr\": \"Ukraine\", \"it\": \"Ucraina\", \"ja\": \"ウクライナ\", \"lt\": \"Ukraina\", \"lv\": \"Ukraina\", \"pl\": \"Ukraina\", \"pt\": \"Ucrânia\", \"ru\": \"Украина\", \"uk\": \"Україна\"}', 146, 'UKR'),
        (3, '{\"be\": \"Беларусь\", \"de\": \"Weißrussland\", \"en\": \"Belarus\", \"es\": \"Bielorrusia\", \"fr\": \"Belorus\", \"it\": \"Bielorussia\", \"ja\": \"ベラルーシ\", \"lt\": \"Baltarusija\", \"lv\": \"Baltkrievija\", \"pl\": \"Białoruś\", \"pt\": \"Bielorrússia\", \"ru\": \"Беларусь\", \"uk\": \"Бiлорусь\"}', 28, 'BLR'),
        (4, '{\"be\": \"Казахстан\", \"de\": \"Kasachstan\", \"en\": \"Kazakhstan\", \"es\": \"Kazajistán\", \"fr\": \"Kazakhstan\", \"it\": \"Kazakistan\", \"ja\": \"カザフスタン\", \"lt\": \"Kazachstanas\", \"lv\": \"Kazahstāna\", \"pl\": \"Kazachstan\", \"pt\": \"Cazaquistão\", \"ru\": \"Казахстан\", \"uk\": \"Казахстан\"}', 83, 'KAZ'),
        (5, '{\"be\": \"Азэрбайджан\", \"de\": \"Aserbaidschan\", \"en\": \"Azerbaijan\", \"es\": \"Azerbaiyán\", \"fr\": \"Azerbaïdjan\", \"it\": \"Azerbaijan\", \"ja\": \"アゼルバイジャン\", \"lt\": \"Azerbaidžanas\", \"lv\": \"Azerbaidžāna\", \"pl\": \"Azerbejdżan\", \"pt\": \"Azerbaijão\", \"ru\": \"Азербайджан\", \"uk\": \"Азербайджан\"}', 12, 'AZE'),
        (6, '{\"be\": \"Арменія\", \"de\": \"Armenien\", \"en\": \"Armenia\", \"es\": \"Armenia\", \"fr\": \"Arménie\", \"it\": \"Armenia\", \"ja\": \"アルメニア\", \"lt\": \"Armėnija\", \"lv\": \"Armēnija\", \"pl\": \"Armenia\", \"pt\": \"Arménia\", \"ru\": \"Армения\", \"uk\": \"Вiрменiя\"}', 6, 'ARM'),
        (7, '{\"be\": \"Грузія\", \"de\": \"Georgien\", \"en\": \"Georgia\", \"es\": \"Georgia\", \"fr\": \"Géorgie\", \"it\": \"Georgia\", \"ja\": \"グルジア\", \"lt\": \"Gruzija\", \"lv\": \"Gruzija\", \"pl\": \"Gruzja\", \"pt\": \"Geórgia\", \"ru\": \"Грузия\", \"uk\": \"Грузiя\"}', 54, 'GEO');");
        } catch (\Exception $e) {
            var_dump("seeding exception");
            var_dump($e->getMessage());
        }
    }
}
