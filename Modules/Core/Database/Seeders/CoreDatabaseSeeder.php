<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\ElPowerAdjustment;
use Modules\Pump\Entities\MainsConnection;
use Modules\Pump\Entities\PumpApplication;
use Modules\Pump\Entities\PumpBrand;
use Modules\Pump\Entities\PumpCategory;
use Modules\Pump\Entities\PumpSeries;
use Modules\Pump\Entities\PumpSeriesAndApplication;
use Modules\Pump\Entities\PumpSeriesAndType;
use Modules\Pump\Entities\PumpType;
use Modules\Selection\Entities\LimitCondition;
use Modules\Selection\Entities\SelectionRange;
use Modules\User\Entities\Business;
use Modules\User\Entities\Permission;
use Modules\User\Entities\Role;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class CoreDatabaseSeeder extends Seeder
{
    use UsesTenantModel;

    /**
     * Run the database seeds.
     *
     * @return void
     */
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

            $tenantGuard = $this->getTenantModel()::current()->getGuard();

            foreach ($permissions as $permission) {
                Permission::create(['guard_name' => $tenantGuard, 'name' => $permission]);
            }

            /** * ROLES AND PERMISSIONS */
            $landlordRole = Role::create(['guard_name' => $tenantGuard, 'name' => 'SuperAdmin']); // check AuthServiceProvider
            foreach ($permissions as $permission) {
                $landlordRole->givePermissionTo($permission);
            }

            /** ADMIN ROLE */
            $adminRole = Role::create(['guard_name' => $tenantGuard, 'name' => 'Admin']);
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

                'project_access',
                'project_create',
                'project_show',
                'project_edit',
                'project_delete',
                'project_restore',
                'project_export',
                'project_clone',

                'pump_access',
                'pump_create',
                'pump_show',
                'pump_edit',
                'pump_delete',
                'pump_restore',

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
            ];

            foreach ($adminPermissions as $permission) {
                $adminRole->givePermissionTo($permission);
            }

            /** CLIENT ROLE */
            $clientRole = Role::create(['guard_name' => $tenantGuard, 'name' => 'Client']);
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
            PumpApplication::create(['name' => ['ru' => "Водозабор", 'en' => 'Water intake']]);
            PumpApplication::create(['name' => ['ru' => "Водоснабжение", 'en' => 'Water supply']]);
            PumpApplication::create(['name' => ['ru' => "Горячее водоснабжение", 'en' => 'Hot water supply']]);
            PumpApplication::create(['name' => ['ru' => "Дождевая вода", 'en' => 'Rain water']]);
            PumpApplication::create(['name' => ['ru' => "Кондиционирование/охлаждение", 'en' => 'Air-conditioning/cooling']]);
            PumpApplication::create(['name' => ['ru' => "Отопление", 'en' => 'Heating']]);
            PumpApplication::create(['name' => ['ru' => "Повышение давления", 'en' => 'Increasing the pressure']]);

            /** * Pump regulations */
            ElPowerAdjustment::create(['name' => ['ru' => "Да", 'en' => 'Yes']]);
            ElPowerAdjustment::create(['name' => ['ru' => "Нет", 'en' => 'No']]);

            /** * Pump categories */
            PumpCategory::create(['name' => ['ru' => "Одинарный", 'en' => 'Single']]);
            PumpCategory::create(['name' => ['ru' => "Сдвоенный", 'en' => 'Double']]);

            /** * Mains phases */
            MainsConnection::create(['id' => 1, 'phase' => 1, 'voltage' => 220]);
            MainsConnection::create(['id' => 3, 'phase' => 3, 'voltage' => 380]);

            /** * DNs */
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

            /** * Pump types */
            PumpType::create(['name' => ['ru' => 'Ин-лайн', 'en' => 'In-line']]);
            PumpType::create(['name' => ['ru' => 'Консольно-моноблочный', 'en' => 'Console monoblock']]);
            PumpType::create(['name' => ['ru' => 'Консольный', 'en' => 'Console']]);
            PumpType::create(['name' => ['ru' => 'Мокрый ротор', 'en' => 'Wet rotor']]);
            PumpType::create(['name' => ['ru' => 'Сухой ротор', 'en' => 'Dry rotor']]);
            PumpType::create(['name' => ['ru' => 'Многоступенчатый', 'en' => 'Multi-stage']]);
            PumpType::create(['name' => ['ru' => 'Центробежный', 'en' => 'Centrifugal']]);
            PumpType::create(['name' => ['ru' => 'Самовсасывающий', 'en' => 'Self-priming']]);
            PumpType::create(['name' => ['ru' => 'Погружной', 'en' => 'Submersible']]);
            PumpType::create(['name' => ['ru' => 'Дренажный', 'en' => 'Drainage']]);
            PumpType::create(['name' => ['ru' => 'Одноступенчатый', 'en' => 'Single-stage']]);

            /** * Limit conditions */
            LimitCondition::create(['value' => '=']);
            LimitCondition::create(['value' => '>=']);
            LimitCondition::create(['value' => '<=']);

            /** * Connection types */
            ConnectionType::create(['name' => ['ru' => 'Резьбовой', 'en' => 'Threaded']]);
            ConnectionType::create(['name' => ['ru' => 'Фланцевый', 'en' => 'Flanged']]);

            /** * Selection ranges */
            SelectionRange::create(['id' => 1, 'name' => ['en' => '1/3', 'ru' => '1/3'], 'value' => 0.33]);
            SelectionRange::create(['id' => 2, 'name' => ['en' => '3/5', 'ru' => '3/5'], 'value' => 0.2]);
            SelectionRange::create(['id' => 3, 'name' => ['en' => 'Custom', 'ru' => 'Произв.'], 'value' => null]);

            /** * Pump Brands */
            PumpBrand::create(['name' => 'Wilo']);

            /** * Pump Series */
            PumpSeries::create(['name' => 'MHI', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 1, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 1, 'type_id' => 6]);
            PumpSeriesAndType::create(['series_id' => 1, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 1, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 1, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 1, 'application_id' => 7]);
            PumpSeries::create(['name' => 'BL', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 2, 'type_id' => 2]);
            PumpSeriesAndType::create(['series_id' => 2, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 2, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 5]);
            PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 6]);
            PumpSeriesAndApplication::create(['series_id' => 2, 'application_id' => 7]);
            PumpSeries::create(['name' => 'TOP-S', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 3, 'type_id' => 4]);
            PumpSeriesAndApplication::create(['series_id' => 3, 'application_id' => 5]);
            PumpSeriesAndApplication::create(['series_id' => 3, 'application_id' => 6]);
            PumpSeries::create(['name' => 'TOP-Z', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 4, 'type_id' => 4]);
            PumpSeriesAndApplication::create(['series_id' => 4, 'application_id' => 3]);
            PumpSeries::create(['name' => 'IL-E', 'brand_id' => 1, 'power_adjustment_id' => 1, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 5, 'type_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 5, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 5, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 5]);
            PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 6]);
            PumpSeriesAndApplication::create(['series_id' => 5, 'application_id' => 7]);
            PumpSeries::create(['name' => 'IPL', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 6, 'type_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 6, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 6, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 5]);
            PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 6]);
            PumpSeriesAndApplication::create(['series_id' => 6, 'application_id' => 7]);
            PumpSeries::create(['name' => 'IL', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 7, 'type_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 7, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 7, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 5]);
            PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 6]);
            PumpSeriesAndApplication::create(['series_id' => 7, 'application_id' => 7]);
            PumpSeries::create(['name' => 'MVI', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 8, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 8, 'type_id' => 6]);
            PumpSeriesAndType::create(['series_id' => 8, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 8, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 8, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 8, 'application_id' => 7]);
            PumpSeries::create(['name' => 'Helix First', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 9, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 9, 'type_id' => 6]);
            PumpSeriesAndType::create(['series_id' => 9, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 9, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 9, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 9, 'application_id' => 7]);
            PumpSeries::create(['name' => 'Helix V', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);
            PumpSeriesAndType::create(['series_id' => 10, 'type_id' => 5]);
            PumpSeriesAndType::create(['series_id' => 10, 'type_id' => 6]);
            PumpSeriesAndType::create(['series_id' => 10, 'type_id' => 7]);
            PumpSeriesAndApplication::create(['series_id' => 10, 'application_id' => 2]);
            PumpSeriesAndApplication::create(['series_id' => 10, 'application_id' => 3]);
            PumpSeriesAndApplication::create(['series_id' => 10, 'application_id' => 7]);
            PumpSeries::create(['name' => 'Stres', 'brand_id' => 1, 'power_adjustment_id' => 2, 'category_id' => 1]);

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
        (7, '{\"be\": \"Грузія\", \"de\": \"Georgien\", \"en\": \"Georgia\", \"es\": \"Georgia\", \"fr\": \"Géorgie\", \"it\": \"Georgia\", \"ja\": \"グルジア\", \"lt\": \"Gruzija\", \"lv\": \"Gruzija\", \"pl\": \"Gruzja\", \"pt\": \"Geórgia\", \"ru\": \"Грузия\", \"uk\": \"Грузiя\"}', 54, 'GEO'),
        (8, '{\"be\": \"Ізраіль\", \"de\": \"Israel\", \"en\": \"Israel\", \"es\": \"Israel\", \"fr\": \"Israël\", \"it\": \"Israele\", \"ja\": \"イスラエル\", \"lt\": \"Izraelis\", \"lv\": \"Izraela\", \"pl\": \"Izrael\", \"pt\": \"Israel\", \"ru\": \"Израиль\", \"uk\": \"Iзраїль\"}', 67, 'ISR'),
        (9, '{\"be\": \"ЗША\", \"de\": \"USA\", \"en\": \"USA\", \"es\": \"EE.UU.\", \"fr\": \"USA\", \"it\": \"Stati Uniti\", \"ja\": \"アメリカ合衆国\", \"lt\": \"JAV\", \"lv\": \"ASV\", \"pl\": \"USA\", \"pt\": \"EUA\", \"ru\": \"США\", \"uk\": \"США\"}', 9, 'USA'),
        (10, '{\"be\": \"Канада\", \"de\": \"Kanada\", \"en\": \"Canada\", \"es\": \"Canadá\", \"fr\": \"Canada\", \"it\": \"Canada\", \"ja\": \"カナダ\", \"lt\": \"Kanada\", \"lv\": \"Kanāda\", \"pl\": \"Kanada\", \"pt\": \"Canadá\", \"ru\": \"Канада\", \"uk\": \"Канада\"}', 30, 'CAN'),
        (11, '{\"be\": \"Кыргызтан\", \"de\": \"Kirgisistan\", \"en\": \"Kyrgyzstan\", \"es\": \"Kirguistán\", \"fr\": \"Kirghizstan\", \"it\": \"Kyrgyzstan\", \"ja\": \"キルギスタン\", \"lt\": \"Kirgizija\", \"lv\": \"Kirgizstāna\", \"pl\": \"Kirgistan\", \"pt\": \"Quirguistão\", \"ru\": \"Кыргызстан\", \"uk\": \"Киргизстан\"}', 76, 'KGZ'),
        (12, '{\"be\": \"Латвія\", \"de\": \"Lettland\", \"en\": \"Latvia\", \"es\": \"Letonia\", \"fr\": \"Lettonie\", \"it\": \"Lettonia\", \"ja\": \"ラトヴィア\", \"lt\": \"Latvija\", \"lv\": \"Latvija\", \"pl\": \"Łotwa\", \"pt\": \"Letónia\", \"ru\": \"Латвия\", \"uk\": \"Латвiя\"}', 90, 'LVA'),
        (13, '{\"be\": \"Летува\", \"de\": \"Litauen\", \"en\": \"Lithuania\", \"es\": \"Lituania\", \"fr\": \"Lituanie\", \"it\": \"Lituania\", \"ja\": \"リトアニア\", \"lt\": \"Lietuva\", \"lv\": \"Lietuva\", \"pl\": \"Litwa\", \"pt\": \"Lituânia\", \"ru\": \"Литва\", \"uk\": \"Литва\"}', 89, 'LTU'),
        (14, '{\"be\": \"Эстонія\", \"de\": \"Estland\", \"en\": \"Estonia\", \"es\": \"Estonia\", \"fr\": \"Estonie\", \"it\": \"Estonia\", \"ja\": \"エストニア\", \"lt\": \"Estija\", \"lv\": \"Igaunija\", \"pl\": \"Estonia\", \"pt\": \"Estónia\", \"ru\": \"Эстония\", \"uk\": \"Естонiя\"}', 1, 'EST'),
        (15, '{\"be\": \"Малдова\", \"de\": \"Moldavien\", \"en\": \"Moldova\", \"es\": \"Moldavia\", \"fr\": \"Moldavie\", \"it\": \"Moldavia\", \"ja\": \"モルドバ\", \"lt\": \"Moldova\", \"lv\": \"Moldova\", \"pl\": \"Mołdawia\", \"pt\": \"Moldávia\", \"ru\": \"Молдова\", \"uk\": \"Молдова\"}', 92, 'MDA'),
        (16, '{\"be\": \"Таджыкістан\", \"de\": \"Tadschikistan\", \"en\": \"Tajikistan\", \"es\": \"Tadjikistán\", \"fr\": \"Tadjikistan\", \"it\": \"Tajikistan\", \"ja\": \"タジキスタン\", \"lt\": \"Tadžikistanas\", \"lv\": \"Tadžikistāna\", \"pl\": \"Tadżykistan\", \"pt\": \"Tadjiquistão\", \"ru\": \"Таджикистан\", \"uk\": \"Таджикистан\"}', 138, 'TJK'),
        (17, '{\"be\": \"Туркмэністан\", \"de\": \"Turkmenistan\", \"en\": \"Turkmenistan\", \"es\": \"Turkmenistán\", \"fr\": \"Turkménistan\", \"it\": \"Turkmenistan\", \"ja\": \"トルクメニスタン\", \"lt\": \"Turkmėnistanas\", \"lv\": \"Turkmenistāna\", \"pl\": \"Turkmenistan\", \"pt\": \"Turquemenistão\", \"ru\": \"Туркменистан\", \"uk\": \"Туркменістан\"}', 139, 'TKM'),
        (18, '{\"be\": \"Узбэкістан\", \"de\": \"Usbekistan\", \"en\": \"Uzbekistan\", \"es\": \"Uzbekistán\", \"fr\": \"Ouzbékistan\", \"it\": \"Uzbekistan\", \"ja\": \"ウズベキスタン\", \"lt\": \"Uzbekistanas\", \"lv\": \"Uzbekistāna\", \"pl\": \"Uzbekistan\", \"pt\": \"Uzbequistão\", \"ru\": \"Узбекистан\", \"uk\": \"Узбекистан\"}', 149, 'UZB'),
        (19, '{\"be\": \"Аўстралія\", \"de\": \"Australien\", \"en\": \"Australia\", \"es\": \"Australia\", \"fr\": \"Australie\", \"it\": \"Australia\", \"ja\": \"オーストラリア\", \"lt\": \"Australija\", \"lv\": \"Austrālija\", \"pl\": \"Australia\", \"pt\": \"Austrália\", \"ru\": \"Австралия\", \"uk\": \"Австралiя\"}', 10, 'AUS'),
        (20, '{\"be\": \"Аўстрыя\", \"de\": \"Österreich\", \"en\": \"Austria\", \"es\": \"Austria\", \"fr\": \"Autriche\", \"it\": \"Austria\", \"ja\": \"オーストリア\", \"lt\": \"Austrija\", \"lv\": \"Austrija\", \"pl\": \"Austria\", \"pt\": \"Áustria\", \"ru\": \"Австрия\", \"uk\": \"Австрiя\"}', 1, 'AUT'),
        (21, '{\"be\": \"Альбанія\", \"de\": \"Albanien\", \"en\": \"Albania\", \"es\": \"Albania\", \"fr\": \"Albanie\", \"it\": \"Albania\", \"ja\": \"アルバニア\", \"lt\": \"Albanija\", \"lv\": \"Albānija\", \"pl\": \"Albania\", \"pt\": \"Albânia\", \"ru\": \"Албания\", \"uk\": \"Албанiя\"}', 5, 'ALB'),
        (22, '{\"be\": \"Альжыр\", \"de\": \"Algerien\", \"en\": \"Algeria\", \"es\": \"Argelia\", \"fr\": \"Algérie\", \"it\": \"Algeria\", \"ja\": \"アルジェリア\", \"lt\": \"Alžyras\", \"lv\": \"Alžīrija\", \"pl\": \"Algeria\", \"pt\": \"Argélia\", \"ru\": \"Алжир\", \"uk\": \"Алжир\"}', 46, 'DZA'),
        (23, '{\"be\": \"Амэрыканскае Самоа\", \"de\": \"Amerikanisch Samoa\", \"en\": \"American Samoa\", \"es\": \"Samoa Americana\", \"fr\": \"Samoa américaines\", \"it\": \"Samoa Americana\", \"ja\": \"アメリカ領サモア\", \"lt\": \"Amerikos Samoa\", \"lv\": \"Amerikas Samoa\", \"pl\": \"Samoa Amerykańskie\", \"pt\": \"Samoa Americana\", \"ru\": \"Американское Самоа\", \"uk\": \"Американське Самоа\"}', 9, 'ASM'),
(24, '{\"be\": \"Анґілья\", \"de\": \"Anguilla\", \"en\": \"Anguilla\", \"es\": \"Anguilla\", \"fr\": \"Anguilla\", \"it\": \"Anguilla\", \"ja\": \"アンギラ\", \"lt\": \"Angilija\", \"lv\": \"Angilja\", \"pl\": \"Anguilla\", \"pt\": \"Anguilla\", \"ru\": \"Ангилья\", \"uk\": \"Ангілья\"}', 4, 'AIA'),
(25, '{\"be\": \"Ангола\", \"de\": \"Angola\", \"en\": \"Angola\", \"es\": \"Angola\", \"fr\": \"Angola\", \"it\": \"Angola\", \"ja\": \"アンゴラ\", \"lt\": \"Angola\", \"lv\": \"Angola\", \"pl\": \"Angola\", \"pt\": \"Angola\", \"ru\": \"Ангола\", \"uk\": \"Ангола\"}', 7, 'AGO'),
(26, '{\"be\": \"Андора\", \"de\": \"Andorra\", \"en\": \"Andorra\", \"es\": \"Andorra\", \"fr\": \"Andorre\", \"it\": \"Andorra\", \"ja\": \"アンドラ\", \"lt\": \"Andora\", \"lv\": \"Andora\", \"pl\": \"Andorra\", \"pt\": \"Andorra\", \"ru\": \"Андорра\", \"uk\": \"Андорра\"}', 1, 'AND'),
(27, '{\"be\": \"Антыгуа і Барбуда\", \"de\": \"Antigua und Barbuda\", \"en\": \"Antigua and Barbuda\", \"es\": \"Antigua y Barbuda\", \"fr\": \"Antigua et Barbuda\", \"it\": \"Antigua e Barbuda\", \"ja\": \"アンティグア・バーブーダ\", \"lt\": \"Antikva ir Barbuda\", \"lv\": \"Antigva un Barbuda\", \"pl\": \"Antigua i Barbuda\", \"pt\": \"Antígua e Barbuda\", \"ru\": \"Антигуа и Барбуда\", \"uk\": \"Антiгуа i Барбуда\"}', 4, 'ATG'),
(28, '{\"be\": \"Аргентына\", \"de\": \"Argentinien\", \"en\": \"Argentina\", \"es\": \"Argentina\", \"fr\": \"Argentine\", \"it\": \"Argentina\", \"ja\": \"アルゼンチン\", \"lt\": \"Argentina\", \"lv\": \"Argentīna\", \"pl\": \"Argentyna\", \"pt\": \"Argentina\", \"ru\": \"Аргентина\", \"uk\": \"Аргентина\"}', 8, 'ARG'),
(29, '{\"be\": \"Аруба\", \"de\": \"Aruba\", \"en\": \"Aruba\", \"es\": \"Aruba\", \"fr\": \"Aruba\", \"it\": \"Aruba\", \"ja\": \"アルバ\", \"lt\": \"Aruba\", \"lv\": \"Aruba\", \"pl\": \"Aruba\", \"pt\": \"Aruba\", \"ru\": \"Аруба\", \"uk\": \"Аруба\"}', 11, 'ABW'),
(30, '{\"be\": \"Аўганістан\", \"de\": \"Afghanistan\", \"en\": \"Afghanistan\", \"es\": \"Afganistán\", \"fr\": \"Afghanistan\", \"it\": \"Afghanistan\", \"ja\": \"アフガニスタン\", \"lt\": \"Afganistanas\", \"lv\": \"Afganistāna\", \"pl\": \"Afganistan\", \"pt\": \"Afeganistão\", \"ru\": \"Афганистан\", \"uk\": \"Афганiстан\"}', 3, 'AFG'),
(31, '{\"be\": \"Багамы\", \"de\": \"Bahamas\", \"en\": \"Bahamas\", \"es\": \"Bahamas\", \"fr\": \"Bahamas\", \"it\": \"Bahamas\", \"ja\": \"バハマ\", \"lt\": \"Bahamai\", \"lv\": \"Bahamu salas\", \"pl\": \"Bahama\", \"pt\": \"Bahamas\", \"ru\": \"Багамы\", \"uk\": \"Багами\"}', 24, 'BHS'),
(32, '{\"be\": \"Бангладэш\", \"de\": \"Bangladesch\", \"en\": \"Bangladesh\", \"es\": \"Bangladesh\", \"fr\": \"Bengladesh\", \"it\": \"Bangladesh\", \"ja\": \"バングラディシュ\", \"lt\": \"Bangladešas\", \"lv\": \"Bangladeša\", \"pl\": \"Bangladesz\", \"pt\": \"Bangladesh\", \"ru\": \"Бангладеш\", \"uk\": \"Бангладеш\"}', 15, 'BGD'),
(33, '{\"be\": \"Барбадос\", \"de\": \"Barbados\", \"en\": \"Barbados\", \"es\": \"Barbados\", \"fr\": \"Barbades\", \"it\": \"Barbados\", \"ja\": \"バルバドス\", \"lt\": \"Barbadosas\", \"lv\": \"Barbadosa\", \"pl\": \"Barbados\", \"pt\": \"Barbados\", \"ru\": \"Барбадос\", \"uk\": \"Барбадос\"}', 14, 'BRB'),
(34, '{\"be\": \"Бахрэйн\", \"de\": \"Bahrain\", \"en\": \"Bahrain\", \"es\": \"Bahréin\", \"fr\": \"Bahreïn\", \"it\": \"Bahrain\", \"ja\": \"バーレーン\", \"lt\": \"Bahreinas\", \"lv\": \"Bahreina\", \"pl\": \"Bahrain\", \"pt\": \"Bahrein\", \"ru\": \"Бахрейн\", \"uk\": \"Бахрейн\"}', 18, 'BHR'),
(35, '{\"be\": \"Бэліз\", \"de\": \"Belize\", \"en\": \"Belize\", \"es\": \"Belice\", \"fr\": \"Bélize\", \"it\": \"Belize\", \"ja\": \"ベリーズ\", \"lt\": \"Belizas\", \"lv\": \"Belīza\", \"pl\": \"Belize\", \"pt\": \"Belize\", \"ru\": \"Белиз\", \"uk\": \"Белiз\"}', 29, 'BLZ'),
(36, '{\"be\": \"Бэльгія\", \"de\": \"Belgien\", \"en\": \"Belgium\", \"es\": \"Bélgica\", \"fr\": \"Belgique\", \"it\": \"Belgio\", \"ja\": \"ベルギー\", \"lt\": \"Belgija\", \"lv\": \"Beļģija\", \"pl\": \"Belgia\", \"pt\": \"Bélgica\", \"ru\": \"Бельгия\", \"uk\": \"Бельгiя\"}', 1, 'BEL'),
(37, '{\"be\": \"Бэнін\", \"de\": \"Benin\", \"en\": \"Benin\", \"es\": \"Benín\", \"fr\": \"Bénin\", \"it\": \"Benin\", \"ja\": \"ベナン\", \"lt\": \"Beninas\", \"lv\": \"Benīna\", \"pl\": \"Benin\", \"pt\": \"Benin\", \"ru\": \"Бенин\", \"uk\": \"Бенiн\"}', 16, 'BEN'),
(38, '{\"be\": \"Бэрмуды\", \"de\": \"Bermudas\", \"en\": \"Bermuda\", \"es\": \"Bermudas\", \"fr\": \"Bermudes\", \"it\": \"Bermuda\", \"ja\": \"バミューダ\", \"lt\": \"Bermudai\", \"lv\": \"Bermudu salas\", \"pl\": \"Bermudy\", \"pt\": \"Bermudas\", \"ru\": \"Бермуды\", \"uk\": \"Бермуди\"}', 20, 'BMU'),
(39, '{\"be\": \"Баўгарыя\", \"de\": \"Bulgarien\", \"en\": \"Bulgaria\", \"es\": \"Bulgaria\", \"fr\": \"Bulgarie\", \"it\": \"Bulgaria\", \"ja\": \"ブルガリア\", \"lt\": \"Bulgarija\", \"lv\": \"Bulgārija\", \"pl\": \"Bułgaria\", \"pt\": \"Bulgária\", \"ru\": \"Болгария\", \"uk\": \"Болгарiя\"}', 17, 'BGR'),
(40, '{\"be\": \"Балівія\", \"de\": \"Bolivien\", \"en\": \"Bolivia\", \"es\": \"Bolivia\", \"fr\": \"Bolivie\", \"it\": \"Bolivia\", \"ja\": \"ボリビア\", \"lt\": \"Bolivija\", \"lv\": \"Bolīvija\", \"pl\": \"Boliwia\", \"pt\": \"Bolívia\", \"ru\": \"Боливия\", \"uk\": \"Болiвiя\"}', 22, 'BOL'),
(41, '{\"be\": \"Босьнія й Герцаґавіна\", \"de\": \"Bosnien-Herzegowina\", \"en\": \"Bosnia and Herzegovina\", \"es\": \"Bosnia y Herzegovina\", \"fr\": \"Bosnie Herzégovine\", \"it\": \"Bosnia Herzegovina\", \"ja\": \"ボスニア・ヘルツェゴビナ\", \"lt\": \"Bosnija ir Hercegovina\", \"lv\": \"Bosnija un Hercogovīna\", \"pl\": \"Bośnia and Herzegowina\", \"pt\": \"Bósnia e Herzegovina\", \"ru\": \"Босния и Герцеговина\", \"uk\": \"Боснiя i Герцеговина\"}', 13, 'BIH'),
(42, '{\"be\": \"Батсвана\", \"de\": \"Botswana\", \"en\": \"Botswana\", \"es\": \"Botswana\", \"fr\": \"Botswana\", \"it\": \"Botswana\", \"ja\": \"ボツワナ\", \"lt\": \"Botsvana\", \"lv\": \"Botstvana\", \"pl\": \"Botswana\", \"pt\": \"Botswana\", \"ru\": \"Ботсвана\", \"uk\": \"Ботсвана\"}', 27, 'BWA'),
(43, '{\"be\": \"Бразылія\", \"de\": \"Brasilien\", \"en\": \"Brazil\", \"es\": \"Brasil\", \"fr\": \"Brésil\", \"it\": \"Brasile\", \"ja\": \"ブラジル\", \"lt\": \"Brazilija\", \"lv\": \"Brazīlija\", \"pl\": \"Brazylia\", \"pt\": \"Brasil\", \"ru\": \"Бразилия\", \"uk\": \"Бразилiя\"}', 23, 'BRA'),
(44, '{\"be\": \"Брунэй-Дарусалам\", \"de\": \"Brunei Darussalam\", \"en\": \"Brunei Darussalam\", \"es\": \"Brunéi\", \"fr\": \"Bruneï\", \"it\": \"Brunei Darussalam\", \"ja\": \"ブルネイ・ダルサラーム\", \"lt\": \"Brunėjaus Dar Es Salamas\", \"lv\": \"Bruneja\", \"pl\": \"Brunei\", \"pt\": \"Brunei Darussalam\", \"ru\": \"Бруней-Даруссалам\", \"uk\": \"Бруней-Дарусалам\"}', 21, 'BRN'),
(45, '{\"be\": \"Буркіна-Фасо\", \"de\": \"Burkina Faso\", \"en\": \"Burkina Faso\", \"es\": \"Burkina Faso\", \"fr\": \"Burkina Faso\", \"it\": \"Burkina Faso\", \"ja\": \"ブルキナファソ\", \"lt\": \"Burkina Faso\", \"lv\": \"Burkinafaso\", \"pl\": \"Burkina Faso\", \"pt\": \"Burkina Faso\", \"ru\": \"Буркина-Фасо\", \"uk\": \"Буркина-Фасо\"}', 16, 'BFA'),
(46, '{\"be\": \"Бурундзі\", \"de\": \"Burundi\", \"en\": \"Burundi\", \"es\": \"Burundi\", \"fr\": \"Burundi\", \"it\": \"Burundi\", \"ja\": \"ブルンジ\", \"lt\": \"Burundis\", \"lv\": \"Burundi\", \"pl\": \"Burundi\", \"pt\": \"Burundi\", \"ru\": \"Бурунди\", \"uk\": \"Бурундi\"}', 19, 'BDI'),
(47, '{\"be\": \"Бутан\", \"de\": \"Bhutan\", \"en\": \"Bhutan\", \"es\": \"Bután\", \"fr\": \"Bouthan\", \"it\": \"Bhutan\", \"ja\": \"ブータン\", \"lt\": \"Butanas\", \"lv\": \"Butāna\", \"pl\": \"Bhutan\", \"pt\": \"Butão\", \"ru\": \"Бутан\", \"uk\": \"Бутан\"}', 25, 'BTN'),
(48, '{\"be\": \"Вануату\", \"de\": \"Vanuatu\", \"en\": \"Vanuatu\", \"es\": \"Vanuatu\", \"fr\": \"Vanuatu\", \"it\": \"Vanuatu\", \"ja\": \"バヌアツ\", \"lt\": \"Vanuatu\", \"lv\": \"Vanuatu\", \"pl\": \"Vanuatu\", \"pt\": \"Vanuatu\", \"ru\": \"Вануату\", \"uk\": \"Вануату\"}', 152, 'VUT'),
(49, '{\"be\": \"Вялікабрытанія\", \"de\": \"Großbritannien\", \"en\": \"United Kingdom\", \"es\": \"Gran Bretaña\", \"fr\": \"Grande-Bretagne\", \"it\": \"Regno Unito\", \"ja\": \"イギリス\", \"lt\": \"Didžioji Britanija\", \"lv\": \"Apvienotā Karaliste\", \"pl\": \"Wielka Brytania\", \"pt\": \"Reino Unido\", \"ru\": \"Великобритания\", \"uk\": \"Великобританiя\"}', 53, 'GBR'),
(50, '{\"be\": \"Вугоршчына\", \"de\": \"Ungarn\", \"en\": \"Hungary\", \"es\": \"Hungría\", \"fr\": \"Hongrie\", \"it\": \"Ungheria\", \"ja\": \"ハンガリー\", \"lt\": \"Vengrija\", \"lv\": \"Ungārija\", \"pl\": \"Węgry\", \"pt\": \"Hungria\", \"ru\": \"Венгрия\", \"uk\": \"Угорщина\"}', 65, 'HUN'),
(51, '{\"be\": \"Вэнэсуэла\", \"de\": \"Venezuela\", \"en\": \"Venezuela\", \"es\": \"Venezuela\", \"fr\": \"Vénézuela\", \"it\": \"Venezuela\", \"ja\": \"ベネズエラ\", \"lt\": \"Venesuela\", \"lv\": \"Venesuela\", \"pl\": \"Wenezuela\", \"pt\": \"Venezuela\", \"ru\": \"Венесуэла\", \"uk\": \"Венесуела\"}', 150, 'VEN'),
(52, '{\"be\": \"Віргінскія выспы, Брытанскія\", \"de\": \"Britische Jungferninseln\", \"en\": \"British Virgin Islands\", \"es\": \"Islas Vírgenes Británicas\", \"fr\": \"Iles Vierges Britanniques\", \"it\": \"Isole Virgin Britanniche\", \"ja\": \"イギリス領ヴァージン諸島\", \"lt\": \"Mergelių salos, Didžioji Britanija\", \"lv\": \"Virdžīnijas salas, Apvienotā Karaliste\", \"pl\": \"Brytyjskie Wyspy Dziewicze\", \"pt\": \"Ilhas Virgens Britânicas\", \"ru\": \"Виргинские острова, Британские\", \"uk\": \"Вiргiнськi острови, Британськi\"}', 9, 'VGB'),
(53, '{\"be\": \"Віргінскія выспы, ЗША\", \"de\": \"US Jungferninseln\", \"en\": \"US Virgin Islands\", \"es\": \"Islas Virginia (EE.UU.)\", \"fr\": \"Iles Vierges Américaines\", \"it\": \"Isole Virgin degli Stati Uniti\", \"ja\": \"アメリカ領ヴァージン諸島\", \"lt\": \"Mergelių salos, JAV\", \"lv\": \"Virdžīnijas salas, ASV\", \"pl\": \"Amerykańskie Wyspy Dziewicze\", \"pt\": \"Ilhas Virgens Americanas\", \"ru\": \"Виргинские острова, США\", \"uk\": \"Вiргiнськi острови, США\"}', 9, 'VIR'),
(55, '{\"be\": \"Віетнам\", \"de\": \"Vietnam\", \"en\": \"Vietnam\", \"es\": \"Vietnam\", \"fr\": \"Vietnam\", \"it\": \"Vietnam\", \"ja\": \"ヴェトナム\", \"lt\": \"Vietnamas\", \"lv\": \"Vjetnama\", \"pl\": \"Wietnam\", \"pt\": \"Vietname\", \"ru\": \"Вьетнам\", \"uk\": \"В\'єтнам\"}', 151, 'VNM'),
(56, '{\"be\": \"Габон\", \"de\": \"Gabon\", \"en\": \"Gabon\", \"es\": \"Gabón\", \"fr\": \"Gabon\", \"it\": \"Gabon\", \"ja\": \"ガボン\", \"lt\": \"Gabonas\", \"lv\": \"Gabona\", \"pl\": \"Gabon\", \"pt\": \"Gabão\", \"ru\": \"Габон\", \"uk\": \"Габон\"}', 32, 'GAB'),
(57, '{\"be\": \"Гаіці\", \"de\": \"Haiti\", \"en\": \"Haiti\", \"es\": \"Haití\", \"fr\": \"Haïti\", \"it\": \"Haiti\", \"ja\": \"ハイチ\", \"lt\": \"Haitis\", \"lv\": \"Haiti\", \"pl\": \"Haiti\", \"pt\": \"Haiti\", \"ru\": \"Гаити\", \"uk\": \"Гаїтi\"}', 64, 'HTI'),
(58, '{\"be\": \"Гаяна\", \"de\": \"Guyana\", \"en\": \"Guyana\", \"es\": \"Guyana\", \"fr\": \"Guyana\", \"it\": \"Guyana\", \"ja\": \"ガイアナ\", \"lt\": \"Gajana\", \"lv\": \"Gajana\", \"pl\": \"Gujana\", \"pt\": \"Guiana\", \"ru\": \"Гайана\", \"uk\": \"Гайана\"}', 60, 'GUY'),
(59, '{\"be\": \"Гамбія\", \"de\": \"Gambia\", \"en\": \"Gambia\", \"es\": \"Gambia\", \"fr\": \"Gambie\", \"it\": \"Gambia\", \"ja\": \"ガンビア\", \"lt\": \"Gambija\", \"lv\": \"Gambija\", \"pl\": \"Gambia\", \"pt\": \"Gâmbia\", \"ru\": \"Гамбия\", \"uk\": \"Гамбiя\"}', 57, 'GMB'),
(60, '{\"be\": \"Гана\", \"de\": \"Ghana\", \"en\": \"Ghana\", \"es\": \"Ghana\", \"fr\": \"Ghana\", \"it\": \"Ghana\", \"ja\": \"ガーナ\", \"lt\": \"Gana\", \"lv\": \"Gana\", \"pl\": \"Ghana\", \"pt\": \"Gana\", \"ru\": \"Гана\", \"uk\": \"Гана\"}', 55, 'GHA'),
(61, '{\"be\": \"Ґўадэлюпа\", \"de\": \"Guadeloupe\", \"en\": \"Guadeloupe\", \"es\": \"Guadalupe (Francia)\", \"fr\": \"Guadeloupe\", \"it\": \"Guadeloupe\", \"ja\": \"グアドループ\", \"lt\": \"Gvadelupa\", \"lv\": \"Gvadelupa\", \"pl\": \"Guadeloupa\", \"pt\": \"Guadalupe\", \"ru\": \"Гваделупа\", \"uk\": \"Гваделупа\"}', 1, 'GLP'),
(62, '{\"be\": \"Гватэмала\", \"de\": \"Guatemala\", \"en\": \"Guatemala\", \"es\": \"Guatemala\", \"fr\": \"Guatemala\", \"it\": \"Guatemala\", \"ja\": \"グアテマラ\", \"lt\": \"Gvatemala\", \"lv\": \"Gvatemala\", \"pl\": \"Guatemala\", \"pt\": \"Guatemala\", \"ru\": \"Гватемала\", \"uk\": \"Гватемала\"}', 59, 'GTM'),
(63, '{\"be\": \"Гвінэя\", \"de\": \"Guinea\", \"en\": \"Guinea\", \"es\": \"Guinea\", \"fr\": \"Guinée\", \"it\": \"Guinea\", \"ja\": \"ギニア\", \"lt\": \"Gvinėja\", \"lv\": \"Gvineja\", \"pl\": \"Gwinea\", \"pt\": \"Guiné\", \"ru\": \"Гвинея\", \"uk\": \"Гвiнея\"}', 58, 'GIN'),
(64, '{\"be\": \"Гвінэя-Бісава\", \"de\": \"Guinea-Bissau\", \"en\": \"Guinea-Bissau\", \"es\": \"Guinea-Bissau\", \"fr\": \"Guinée Bissau\", \"it\": \"Guinea-Bissau\", \"ja\": \"ギニア・ビサウ\", \"lt\": \"Gvinėja Bisau\", \"lv\": \"Gvineja-Bisava\", \"pl\": \"Gwinea-Bissau\", \"pt\": \"Guiné-Bissau\", \"ru\": \"Гвинея-Бисау\", \"uk\": \"Гвiнея-Бiсау\"}', 16, 'GNB'),
(65, '{\"be\": \"Нямеччына\", \"de\": \"Deutschland\", \"en\": \"Germany\", \"es\": \"Alemania\", \"fr\": \"Allemagne\", \"it\": \"Germania\", \"ja\": \"ドイツ\", \"lt\": \"Vokietija\", \"lv\": \"Vācija\", \"pl\": \"Niemcy\", \"pt\": \"Alemanha\", \"ru\": \"Германия\", \"uk\": \"Нiмеччина\"}', 1, 'DEU'),
(66, '{\"be\": \"Гібралтар\", \"de\": \"Gibraltar\", \"en\": \"Gibraltar\", \"es\": \"Gibraltar\", \"fr\": \"Gibraltar\", \"it\": \"Gibilterra\", \"ja\": \"ジブラルタル\", \"lt\": \"Gibraltaras\", \"lv\": \"Gibraltāra\", \"pl\": \"Gibraltar\", \"pt\": \"Gibraltar\", \"ru\": \"Гибралтар\", \"uk\": \"Гiбралтар\"}', 56, 'GIB'),
(67, '{\"be\": \"Гандурас\", \"de\": \"Honduras\", \"en\": \"Honduras\", \"es\": \"Honduras\", \"fr\": \"Honduras\", \"it\": \"Honduras\", \"ja\": \"ホンジュラス\", \"lt\": \"Hondūras\", \"lv\": \"Gondurasa\", \"pl\": \"Honduras\", \"pt\": \"Gordura\", \"ru\": \"Гондурас\", \"uk\": \"Гондурас\"}', 62, 'HND'),
(68, '{\"be\": \"Ганконг\", \"de\": \"Hong Kong\", \"en\": \"Hong Kong\", \"es\": \"Hong Kong\", \"fr\": \"Hong Kong\", \"it\": \"Hong Kong\", \"ja\": \"香港\", \"lt\": \"Honkongas\", \"lv\": \"Gonkonga\", \"pl\": \"Hong Kong\", \"pt\": \"Hong Kong\", \"ru\": \"Гонконг\", \"uk\": \"Гонконг\"}', 61, 'HKG'),
(69, '{\"be\": \"Грэнада\", \"de\": \"Grenada\", \"en\": \"Grenada\", \"es\": \"Granada\", \"fr\": \"Grenade\", \"it\": \"Grenada\", \"ja\": \"グレナダ\", \"lt\": \"Grenada\", \"lv\": \"Granāda\", \"pl\": \"Grenada\", \"pt\": \"Granada\", \"ru\": \"Гренада\", \"uk\": \"Гренада\"}', 4, 'GRD'),
(70, '{\"be\": \"Грэнляндыя\", \"de\": \"Grönland\", \"en\": \"Greenland\", \"es\": \"Groenlandia\", \"fr\": \"Groenland\", \"it\": \"Groenlandia\", \"ja\": \"グリーンランド\", \"lt\": \"Grenlandija\", \"lv\": \"Grenlande\", \"pl\": \"Grenlandia\", \"pt\": \"Gronelândia<br>\", \"ru\": \"Гренландия\", \"uk\": \"Гренландiя\"}', 44, 'GRL'),
(71, '{\"be\": \"Грэцыя\", \"de\": \"Griechenland\", \"en\": \"Greece\", \"es\": \"Grecia\", \"fr\": \"Grèce\", \"it\": \"Grecia\", \"ja\": \"ギリシャ\", \"lt\": \"Graikija\", \"lv\": \"Grieķija\", \"pl\": \"Grecja\", \"pt\": \"Grécia\", \"ru\": \"Греция\", \"uk\": \"Грецiя\"}', 1, 'GRC'),
(72, '{\"be\": \"Гуам\", \"de\": \"Guam\", \"en\": \"Guam\", \"es\": \"Guam\", \"fr\": \"Guam\", \"it\": \"Guam\", \"ja\": \"グアム\", \"lt\": \"Guamas\", \"lv\": \"Guama\", \"pl\": \"Guam\", \"pt\": \"Guam\", \"ru\": \"Гуам\", \"uk\": \"Гуам\"}', 9, 'GUM'),
(73, '{\"be\": \"Данія\", \"de\": \"Dänemark\", \"en\": \"Denmark\", \"es\": \"Dinamarca\", \"fr\": \"Danemark\", \"it\": \"Danimarca\", \"ja\": \"デンマーク\", \"lt\": \"Danija\", \"lv\": \"Dānija\", \"pl\": \"Dania\", \"pt\": \"Dinamarca\", \"ru\": \"Дания\", \"uk\": \"Данiя\"}', 44, 'DNK'),
(74, '{\"be\": \"Дамініка\", \"de\": \"Dominica\", \"en\": \"Dominica\", \"es\": \"Dominica\", \"fr\": \"Dominique\", \"it\": \"Dominica\", \"ja\": \"ドミニカ\", \"lt\": \"Dominika\", \"lv\": \"Dominika\", \"pl\": \"Dominika\", \"pt\": \"Dominica\", \"ru\": \"Доминика\", \"uk\": \"Домiнiка\"}', 4, 'DMA'),
(75, '{\"be\": \"Дамініканская Рэспубліка\", \"de\": \"Dominikanische Republik\", \"en\": \"Dominican Republic\", \"es\": \"República Dominicana\", \"fr\": \"République dominicaine\", \"it\": \"Repubblica Domenicana\", \"ja\": \"ドミニカ共和国\", \"lt\": \"Dominikos Respublika\", \"lv\": \"Dominikānas Republika\", \"pl\": \"Dominikana\", \"pt\": \"República Dominicana\", \"ru\": \"Доминиканская Республика\", \"uk\": \"Домiнiканська Республiка\"}', 45, 'DOM'),
(76, '{\"be\": \"Эгіпэт\", \"de\": \"Ägypten\", \"en\": \"Egypt\", \"es\": \"Egipto\", \"fr\": \"Egypte\", \"it\": \"Egitto\", \"ja\": \"エジプト\", \"lt\": \"Egiptas\", \"lv\": \"Ēģipte\", \"pl\": \"Egipt\", \"pt\": \"Egito\", \"ru\": \"Египет\", \"uk\": \"Єгипет\"}', 47, 'EGY'),
(77, '{\"be\": \"Замбія\", \"de\": \"Sambia\", \"en\": \"Zambia\", \"es\": \"Zambia\", \"fr\": \"Zambie\", \"it\": \"Egitto\", \"ja\": \"ザンビア\", \"lt\": \"Zambija\", \"lv\": \"Zambija\", \"pl\": \"Zambia\", \"pt\": \"Zâmbia\", \"ru\": \"Замбия\", \"uk\": \"Замбiя\"}', 156, 'ZMB'),
(78, '{\"be\": \"Заходняя Сахара\", \"de\": \"Westsahara\", \"en\": \"Western Sahara\", \"es\": \"Sáhara Occidental\", \"fr\": \"Sahara occidental\", \"it\": \"Sahara Occidentale\", \"ja\": \"西サハラ\", \"lt\": \"Vakarų Sachara\", \"lv\": \"Rietumsahāra\", \"pl\": \"Sahara Zachodnia\", \"pt\": \"Saara Ocidental\", \"ru\": \"Западная Сахара\", \"uk\": \"Захiдна Сахара\"}', 48, 'ESH'),
(79, '{\"be\": \"Зімбабвэ\", \"de\": \"Simbabwe\", \"en\": \"Zimbabwe\", \"es\": \"Zimbabue\", \"fr\": \"Zimbabwe\", \"it\": \"Zimbabwe\", \"ja\": \"ジンバブエ\", \"lt\": \"Zimbabvė\", \"lv\": \"Zimbabve\", \"pl\": \"Zimbabwe\", \"pt\": \"Zimbabwe\", \"ru\": \"Зимбабве\", \"uk\": \"Зiмбабве\"}', 157, 'ZWE'),
(80, '{\"be\": \"Індыя\", \"de\": \"Indien\", \"en\": \"India\", \"es\": \"India\", \"fr\": \"Inde\", \"it\": \"India\", \"ja\": \"インド\", \"lt\": \"Indija\", \"lv\": \"Indija\", \"pl\": \"Indie\", \"pt\": \"Índia\", \"ru\": \"Индия\", \"uk\": \"Iндiя\"}', 68, 'IND'),
(81, '{\"be\": \"Інданэзія\", \"de\": \"Indonesien\", \"en\": \"Indonesia\", \"es\": \"Indonesia\", \"fr\": \"Indonésie\", \"it\": \"Indonesia\", \"ja\": \"インドネシア\", \"lt\": \"Indonezija\", \"lv\": \"Indonēzija\", \"pl\": \"Indonezja\", \"pt\": \"Indonésia\", \"ru\": \"Индонезия\", \"uk\": \"Iндонезiя\"}', 66, 'IDN'),
(82, '{\"be\": \"Іярданія\", \"de\": \"Jordanien\", \"en\": \"Jordan\", \"es\": \"Jordania\", \"fr\": \"Jordanie\", \"it\": \"Giordania\", \"ja\": \"ヨルダン\", \"lt\": \"Jordanija\", \"lv\": \"Jordānija\", \"pl\": \"Jordania\", \"pt\": \"Jordânia\", \"ru\": \"Иордания\", \"uk\": \"Йорданiя\"}', 73, 'JOR'),
(83, '{\"be\": \"Ірак\", \"de\": \"Irak\", \"en\": \"Iraq\", \"es\": \"Irak\", \"fr\": \"Irak\", \"it\": \"Iraq\", \"ja\": \"イラク\", \"lt\": \"Irakas\", \"lv\": \"Irāka\", \"pl\": \"Irak\", \"pt\": \"Iraque\", \"ru\": \"Ирак\", \"uk\": \"Iрак\"}', 69, 'IRQ'),
(84, '{\"be\": \"Іран\", \"de\": \"Iran\", \"en\": \"Iran\", \"es\": \"Irán\", \"fr\": \"Iran\", \"it\": \"Iran\", \"ja\": \"イラン\", \"lt\": \"Iranas\", \"lv\": \"Irāna\", \"pl\": \"Iran\", \"pt\": \"Irão\", \"ru\": \"Иран\", \"uk\": \"Iран\"}', 70, 'IRN'),
(85, '{\"be\": \"Ірляндыя\", \"de\": \"Irland\", \"en\": \"Ireland\", \"es\": \"Irlanda\", \"fr\": \"Irlande\", \"it\": \"Irlanda\", \"ja\": \"アイルランド\", \"lt\": \"Airija\", \"lv\": \"Īrija\", \"pl\": \"Irlandia\", \"pt\": \"Irlanda\", \"ru\": \"Ирландия\", \"uk\": \"Iрландiя\"}', 1, 'IRL'),
(86, '{\"be\": \"Ісьляндыя\", \"de\": \"Island\", \"en\": \"Iceland\", \"es\": \"Islandia\", \"fr\": \"Islande\", \"it\": \"Islanda\", \"ja\": \"アイスランド\", \"lt\": \"Islandija\", \"lv\": \"Īslande\", \"pl\": \"Islandia\", \"pt\": \"Islândia\", \"ru\": \"Исландия\", \"uk\": \"Iсландiя\"}', 71, 'ISL'),
(87, '{\"be\": \"Гішпанія\", \"de\": \"Spanien\", \"en\": \"Spain\", \"es\": \"España\", \"fr\": \"Espagne\", \"it\": \"Spagna\", \"ja\": \"スペイン\", \"lt\": \"Ispanija\", \"lv\": \"Spānija\", \"pl\": \"Hiszpania\", \"pt\": \"Espanha\", \"ru\": \"Испания\", \"uk\": \"Iспанiя\"}', 1, 'ESP'),
(88, '{\"be\": \"Італія\", \"de\": \"Italien\", \"en\": \"Italy\", \"es\": \"Italia\", \"fr\": \"Italie\", \"it\": \"Italia\", \"ja\": \"イタリア\", \"lt\": \"Italija\", \"lv\": \"Itālija\", \"pl\": \"Włochy\", \"pt\": \"Itália\", \"ru\": \"Италия\", \"uk\": \"Iталiя\"}', 1, 'ITA'),
(89, '{\"be\": \"Емэн\", \"de\": \"Jemen\", \"en\": \"Yemen\", \"es\": \"Yemen\", \"fr\": \"Yémen\", \"it\": \"Yemen\", \"ja\": \"イエメン\", \"lt\": \"Jemenas\", \"lv\": \"Jemena\", \"pl\": \"Jemen\", \"pt\": \"Iémen\", \"ru\": \"Йемен\", \"uk\": \"Йемен\"}', 154, 'YEM'),
(90, '{\"be\": \"Каба-Вэрдэ\", \"de\": \"Kap Verde\", \"en\": \"Cape Verde\", \"es\": \"Cabo Verde\", \"fr\": \"Cap Vert\", \"it\": \"Cabo Verde\", \"ja\": \"カーボベルデ\", \"lt\": \"Žaliasis Kyšulys\", \"lv\": \"Kaboverde\", \"pl\": \"Cape Verde\", \"pt\": \"Cabo Verde\", \"ru\": \"Кабо-Верде\", \"uk\": \"Кабо-Верде\"}', 40, 'CPV'),
(91, '{\"be\": \"Камбоджа\", \"de\": \"Kambodscha\", \"en\": \"Cambodia\", \"es\": \"Camboya\", \"fr\": \"Cambodge\", \"it\": \"Cambogia\", \"ja\": \"カンボジア\", \"lt\": \"Kambodža\", \"lv\": \"Kambodža\", \"pl\": \"Kambodża\", \"pt\": \"Camboja\", \"ru\": \"Камбоджа\", \"uk\": \"Камбоджа\"}', 77, 'KHM'),
(92, '{\"be\": \"Камэрун\", \"de\": \"Kamerun\", \"en\": \"Cameroon\", \"es\": \"Camerún\", \"fr\": \"Cameroun\", \"it\": \"Camerun\", \"ja\": \"カメルーン\", \"lt\": \"Kamerūnas\", \"lv\": \"Kameruna\", \"pl\": \"Kamerun\", \"pt\": \"Camarões\", \"ru\": \"Камерун\", \"uk\": \"Камерун\"}', 32, 'CMR'),
(93, '{\"be\": \"Катар\", \"de\": \"Katar\", \"en\": \"Qatar\", \"es\": \"Qatar\", \"fr\": \"Qatar\", \"it\": \"Qatar\", \"ja\": \"カタール\", \"lt\": \"Kataras\", \"lv\": \"Katāra\", \"pl\": \"Katar\", \"pt\": \"Qatar\", \"ru\": \"Катар\", \"uk\": \"Катар\"}', 118, 'QAT'),
(94, '{\"be\": \"Кенія\", \"de\": \"Kenia\", \"en\": \"Kenya\", \"es\": \"Kenia\", \"fr\": \"Kenya\", \"it\": \"Kenia\", \"ja\": \"ケニア\", \"lt\": \"Kenija\", \"lv\": \"Kēnija\", \"pl\": \"Kenia\", \"pt\": \"Quénia\", \"ru\": \"Кения\", \"uk\": \"Кенiя\"}', 75, 'KEN'),
(95, '{\"be\": \"Кіпар\", \"de\": \"Zypern\", \"en\": \"Cyprus\", \"es\": \"Chipre\", \"fr\": \"Chypre\", \"it\": \"Cipro\", \"ja\": \"キプロス\", \"lt\": \"Kipras\", \"lv\": \"Kipra\", \"pl\": \"Cypr\", \"pt\": \"Chipre\", \"ru\": \"Кипр\", \"uk\": \"Кiпр\"}', 1, 'CYP'),
(96, '{\"be\": \"Кірыбаты\", \"de\": \"Kiribati\", \"en\": \"Kiribati\", \"es\": \"Kiribati\", \"fr\": \"Kiribati\", \"it\": \"Kiribati\", \"ja\": \"キリバス\", \"lt\": \"Kiribatis\", \"lv\": \"Ķiribati\", \"pl\": \"Kiribati\", \"pt\": \"Kiribati\", \"ru\": \"Кирибати\", \"uk\": \"Кiрiбатi\"}', 10, 'KIR'),
(97, '{\"be\": \"Кітай\", \"de\": \"China\", \"en\": \"China\", \"es\": \"China\", \"fr\": \"Chine\", \"it\": \"Cina\", \"ja\": \"中国\", \"lt\": \"Kinija\", \"lv\": \"Ķīna\", \"pl\": \"Chiny\", \"pt\": \"China\", \"ru\": \"Китай\", \"uk\": \"Китай\"}', 36, 'CHN'),
(98, '{\"be\": \"Калюмбія\", \"de\": \"Kolumbien\", \"en\": \"Colombia\", \"es\": \"Colombia\", \"fr\": \"Colombie\", \"it\": \"Colombia\", \"ja\": \"コロンビア\", \"lt\": \"Kolumbija\", \"lv\": \"Kolumbija\", \"pl\": \"Kolumbia\", \"pt\": \"Colômbia\", \"ru\": \"Колумбия\", \"uk\": \"Колумбiя\"}', 37, 'COL'),
(99, '{\"be\": \"Каморы\", \"de\": \"Komoren\", \"en\": \"Comoros\", \"es\": \"Comoras\", \"fr\": \"Comores\", \"it\": \"Comoros\", \"ja\": \"コモロ諸島\", \"lt\": \"Komorai\", \"lv\": \"Komoru salas\", \"pl\": \"Komory\", \"pt\": \"Comores\", \"ru\": \"Коморы\", \"uk\": \"Комори\"}', 78, 'COM'),
(100, '{\"be\": \"Конга\", \"de\": \"Kongo\", \"en\": \"Congo\", \"es\": \"Congo\", \"fr\": \"Congo\", \"it\": \"Congo\", \"ja\": \"コンゴ\", \"lt\": \"Kongas\", \"lv\": \"Kongo\", \"pl\": \"Kongo\", \"pt\": \"Congo\", \"ru\": \"Конго\", \"uk\": \"Конго\"}', 32, 'COG'),
(101, '{\"be\": \"Конга, дэмакратычная рэспубліка\", \"de\": \"Kongo, Demokratische Republik\", \"en\": \"Congo, Democratic Republic\", \"es\": \"República Democrática del Congo\", \"fr\": \"République démocratique du Congo\", \"it\": \"Congo, Repubblica Democratica\", \"ja\": \"コンゴ民主共和国\", \"lt\": \"Kongo Demokratinė Respublika\", \"lv\": \"Kongo Demokrātiskā Republika\", \"pl\": \"Demokratyczna Republika Konga\", \"pt\": \"República Democrática do Congo\", \"ru\": \"Конго, демократическая республика\", \"uk\": \"Конго, демократична республiка\"}', 31, 'COD'),
(102, '{\"be\": \"Коста-Рыка\", \"de\": \"Costa Rica\", \"en\": \"Costa Rica\", \"es\": \"Costa Rica\", \"fr\": \"Costa Rica\", \"it\": \"Costa Rica\", \"ja\": \"コスタリカ\", \"lt\": \"Kosta Rika\", \"lv\": \"Kostarika\", \"pl\": \"Costa Rica\", \"pt\": \"Costa Rica\", \"ru\": \"Коста-Рика\", \"uk\": \"Коста-Рiка\"}', 38, 'CRI'),
(104, '{\"be\": \"Куба\", \"de\": \"Kuba\", \"en\": \"Cuba\", \"es\": \"Cuba\", \"fr\": \"Cuba\", \"it\": \"Cuba\", \"ja\": \"キューバ\", \"lt\": \"Kuba\", \"lv\": \"Kuba\", \"pl\": \"Kuba\", \"pt\": \"Cuba\", \"ru\": \"Куба\", \"uk\": \"Куба\"}', 39, 'CUB'),
(105, '{\"be\": \"Кувэйт\", \"de\": \"Kuwait\", \"en\": \"Kuwait\", \"es\": \"Kuwait\", \"fr\": \"Koweït\", \"it\": \"Kuwait\", \"ja\": \"クウェート\", \"lt\": \"Kuveitas\", \"lv\": \"Kuveita\", \"pl\": \"Kuwejt\", \"pt\": \"Kuwait<br>\", \"ru\": \"Кувейт\", \"uk\": \"Кувейт\"}', 81, 'KWT'),
(106, '{\"be\": \"Ляос\", \"de\": \"Laos\", \"en\": \"Laos\", \"es\": \"Laos\", \"fr\": \"Laos\", \"it\": \"Laos\", \"ja\": \"ラオス\", \"lt\": \"Laosas\", \"lv\": \"Laosa\", \"pl\": \"Laos\", \"pt\": \"Laos\", \"ru\": \"Лаос\", \"uk\": \"Лаос\"}', 84, 'LAO'),
(107, '{\"be\": \"Лесота\", \"de\": \"Lesotho\", \"en\": \"Lesotho\", \"es\": \"Lesoto\", \"fr\": \"Leshoto\", \"it\": \"Lesotho\", \"ja\": \"レソト\", \"lt\": \"Lesotas\", \"lv\": \"Lesoto\", \"pl\": \"Lesotho\", \"pt\": \"Lesoto\", \"ru\": \"Лесото\", \"uk\": \"Лесото\"}', 88, 'LSO'),
(108, '{\"be\": \"Лібэрыя\", \"de\": \"Liberia\", \"en\": \"Liberia\", \"es\": \"Liberia\", \"fr\": \"Libéria\", \"it\": \"Liberia\", \"ja\": \"リベリア\", \"lt\": \"Liberija\", \"lv\": \"Libērija\", \"pl\": \"Liberia\", \"pt\": \"Libéria\", \"ru\": \"Либерия\", \"uk\": \"Лiберiя\"}', 87, 'LBR'),
(109, '{\"be\": \"Лібан\", \"de\": \"Libanon\", \"en\": \"Lebanon\", \"es\": \"Líbano\", \"fr\": \"Liban\", \"it\": \"Libano\", \"ja\": \"レバノン\", \"lt\": \"Libanas\", \"lv\": \"Livāna\", \"pl\": \"Liban\", \"pt\": \"Líbano\", \"ru\": \"Ливан\", \"uk\": \"Лiван\"}', 85, 'LBN'),
(110, '{\"be\": \"Лібія\", \"de\": \"Libyen\", \"en\": \"Libya\", \"es\": \"Libia\", \"fr\": \"Lybie\", \"it\": \"Libia\", \"ja\": \"リビア\", \"lt\": \"Libija \", \"lv\": \"Lībija\", \"pl\": \"Libia\", \"pt\": \"Líbia\", \"ru\": \"Ливия\", \"uk\": \"Лiвiя\"}', 91, 'LBY'),
(111, '{\"be\": \"Ліхтэнштайн\", \"de\": \"Liechtenstein\", \"en\": \"Liechtenstein\", \"es\": \"Liechtenstein\", \"fr\": \"Liechtenstein\", \"it\": \"Liechtenstein\", \"ja\": \"リヒテンシュタイン\", \"lt\": \"Lichtenšteinas\", \"lv\": \"Lihtenšteina\", \"pl\": \"Liechtenstein\", \"pt\": \"Liechtenstein\", \"ru\": \"Лихтенштейн\", \"uk\": \"Лiхтенштейн\"}', 33, 'LIE'),
(112, '{\"be\": \"Люксэмбург\", \"de\": \"Luxemburg\", \"en\": \"Luxembourg\", \"es\": \"Luxemburgo\", \"fr\": \"Luxembourg\", \"it\": \"Lussemburgo\", \"ja\": \"ルクセンブルク\", \"lt\": \"Liuksemburgas\", \"lv\": \"Luksemburga\", \"pl\": \"Luxembourg\", \"pt\": \"Luxemburgo\", \"ru\": \"Люксембург\", \"uk\": \"Люксембург\"}', 1, 'LUX'),
(113, '{\"be\": \"Маўрыцы\", \"de\": \"Mauritius\", \"en\": \"Mauritius\", \"es\": \"Mauricio\", \"fr\": \"Maurice\", \"it\": \"Mauritius\", \"ja\": \"モーリシャス\", \"lt\": \"Mauricijus\", \"lv\": \"Mavrikija\", \"pl\": \"Mauritius\", \"pt\": \"Maurícia\", \"ru\": \"Маврикий\", \"uk\": \"Маврикiй\"}', 99, 'MUS'),
(114, '{\"be\": \"Маўрытанія\", \"de\": \"Mauretanien\", \"en\": \"Mauritania\", \"es\": \"Mauritania\", \"fr\": \"Mauritanie\", \"it\": \"Mauritania\", \"ja\": \"モーリタニア\", \"lt\": \"Mauritanija\", \"lv\": \"Mavritānija\", \"pl\": \"Mauretania\", \"pt\": \"Mauritânia\", \"ru\": \"Мавритания\", \"uk\": \"Мавританiя\"}', 98, 'MRT'),
(115, '{\"be\": \"Мадагаскар\", \"de\": \"Madagaskar\", \"en\": \"Madagascar\", \"es\": \"Madagascar\", \"fr\": \"Madagascar\", \"it\": \"Madagascar\", \"ja\": \"マダガスカル\", \"lt\": \"Madagaskaras\", \"lv\": \"Madagaskāra\", \"pl\": \"Madagaskar\", \"pt\": \"Madagáscar\", \"ru\": \"Мадагаскар\", \"uk\": \"Мадагаскар\"}', 93, 'MDG'),
(116, '{\"be\": \"Макаа\", \"de\": \"Macao\", \"en\": \"Macau\", \"es\": \"Macao\", \"fr\": \"Macao\", \"it\": \"Macao\", \"ja\": \"マカオ\", \"lt\": \"Macao\", \"lv\": \"Makao\", \"pl\": \"Makao\", \"pt\": \"Macau\", \"ru\": \"Макао\", \"uk\": \"Макао\"}', 97, 'MAC'),
(117, '{\"be\": \"Македонія\", \"de\": \"Mazedonien\", \"en\": \"Macedonia\", \"es\": \"Macedonia\", \"fr\": \"Macédoine\", \"it\": \"Macedonia\", \"ja\": \"マケドニア\", \"lt\": \"Makedonija\", \"lv\": \"Makedonija\", \"pl\": \"Macedonia\", \"pt\": \"Macedónia\", \"ru\": \"Македония\", \"uk\": \"Македонiя\"}', 94, 'MKD'),
(118, '{\"be\": \"Малаві\", \"de\": \"Malawi\", \"en\": \"Malawi\", \"es\": \"Malawi\", \"fr\": \"Malawi\", \"it\": \"Malawi\", \"ja\": \"マラウィ\", \"lt\": \"Malavis<br>\", \"lv\": \"Malavi\", \"pl\": \"Malawi\", \"pt\": \"Malawi\", \"ru\": \"Малави\", \"uk\": \"Малавi\"}', 101, 'MWI'),
(119, '{\"be\": \"Малайзія\", \"de\": \"Malaysia\", \"en\": \"Malaysia\", \"es\": \"Malasia\", \"fr\": \"Malaisie\", \"it\": \"Malesia\", \"ja\": \"マレーシア\", \"lt\": \"Malaizija\", \"lv\": \"Malaizija\", \"pl\": \"Malezja\", \"pt\": \"Malásia\", \"ru\": \"Малайзия\", \"uk\": \"Малайзiя\"}', 103, 'MYS'),
(120, '{\"be\": \"Малі\", \"de\": \"Mali\", \"en\": \"Mali\", \"es\": \"Mali\", \"fr\": \"Mali\", \"it\": \"Mali\", \"ja\": \"マリ\", \"lt\": \"Malis\", \"lv\": \"Mali\", \"pl\": \"Mali\", \"pt\": \"Mali\", \"ru\": \"Мали\", \"uk\": \"Малi\"}', 16, 'MLI'),
(121, '{\"be\": \"Мальдывы\", \"de\": \"Malediven\", \"en\": \"Maldives\", \"es\": \"Maldivas\", \"fr\": \"Maldives\", \"it\": \"Maldive\", \"ja\": \"モルジブ\", \"lt\": \"Maldyvai\", \"lv\": \"Maldīvas\", \"pl\": \"Malediwy\", \"pt\": \"Maldivas\", \"ru\": \"Мальдивы\", \"uk\": \"Мальдiви\"}', 100, 'MDV'),
(122, '{\"be\": \"Мальта\", \"de\": \"Malta\", \"en\": \"Malta\", \"es\": \"Malta\", \"fr\": \"Malte\", \"it\": \"Malta\", \"ja\": \"マルタ\", \"lt\": \"Malta\", \"lv\": \"Malta\", \"pl\": \"Malta\", \"pt\": \"Malta\", \"ru\": \"Мальта\", \"uk\": \"Мальта\"}', 1, 'MLT'),
(123, '{\"be\": \"Марока\", \"de\": \"Marokko\", \"en\": \"Morocco\", \"es\": \"Marruecos\", \"fr\": \"Maroc\", \"it\": \"Marocco\", \"ja\": \"モロッコ\", \"lt\": \"Marokas\", \"lv\": \"Maroka\", \"pl\": \"Maroko\", \"pt\": \"Marrocos\", \"ru\": \"Марокко\", \"uk\": \"Марокко\"}', 48, 'MAR'),
(124, '{\"be\": \"Мартыніка\", \"de\": \"Martinique\", \"en\": \"Martinique\", \"es\": \"Martinica\", \"fr\": \"Martinique\", \"it\": \"Martinique\", \"ja\": \"マルティニク\", \"lt\": \"Martinika\", \"lv\": \"Martinika\", \"pl\": \"Martynika\", \"pt\": \"Martinica\", \"ru\": \"Мартиника\", \"uk\": \"Мартинiка\"}', 1, 'MTQ'),
(125, '{\"be\": \"Маршалавыя Выспы\", \"de\": \"Marshallinseln\", \"en\": \"Marshall Islands\", \"es\": \"Islas Marshall\", \"fr\": \"Iles Marshall\", \"it\": \"Isole Marshall\", \"ja\": \"マーシャル諸島\", \"lt\": \"Maršalų salos\", \"lv\": \"Maršalu salas\", \"pl\": \"Wyspy Marshalla\", \"pt\": \"Ilhas Marshall\", \"ru\": \"Маршалловы Острова\", \"uk\": \"Маршаловi острови\"}', 9, 'MHL'),
(126, '{\"be\": \"Мэксыка\", \"de\": \"Mexiko\", \"en\": \"Mexico\", \"es\": \"México\", \"fr\": \"Mexique\", \"it\": \"Messico\", \"ja\": \"メキシコ\", \"lt\": \"Meksika\", \"lv\": \"Meksika\", \"pl\": \"Meksyk\", \"pt\": \"México\", \"ru\": \"Мексика\", \"uk\": \"Мексика\"}', 102, 'MEX'),
(127, '{\"be\": \"Мікранэзія, фэдэратыўныя штаты\", \"de\": \"Mikronesien\", \"en\": \"Micronesia\", \"es\": \"Estados Federados de Micronesia\", \"fr\": \"Etats fédérés de Micronésie\", \"it\": \"Micronesia\", \"ja\": \"ミクロネシア連邦\", \"lt\": \"Mikronezijos Federacinės Valstijos\", \"lv\": \"Mikronēzija\", \"pl\": \"Mikronezja\", \"pt\": \"Micronésia\", \"ru\": \"Микронезия, федеративные штаты\", \"uk\": \"Мiкронезiя, федеративнi штати\"}', 9, 'FSM'),
(128, '{\"be\": \"Мазамбік\", \"de\": \"Mosambik\", \"en\": \"Mozambique\", \"es\": \"Mozambique\", \"fr\": \"Mozambique\", \"it\": \"Mozambico\", \"ja\": \"モザンビーク\", \"lt\": \"Mozambikas\", \"lv\": \"Mozambika\", \"pl\": \"Mozambik\", \"pt\": \"Moçambique\", \"ru\": \"Мозамбик\", \"uk\": \"Мозамбiк\"}', 104, 'MOZ'),
(129, '{\"be\": \"Манака\", \"de\": \"Monaco\", \"en\": \"Monaco\", \"es\": \"Mónaco\", \"fr\": \"Monaco\", \"it\": \"Monaco\", \"ja\": \"モナコ\", \"lt\": \"Monakas\", \"lv\": \"Monako\", \"pl\": \"Monako\", \"pt\": \"Mónaco\", \"ru\": \"Монако\", \"uk\": \"Монако\"}', 1, 'MCO'),
(130, '{\"be\": \"Манголія\", \"de\": \"Mongolei\", \"en\": \"Mongolia\", \"es\": \"Mongolia\", \"fr\": \"Mongolie\", \"it\": \"Mongolia\", \"ja\": \"モンゴル\", \"lt\": \"Mongolija\", \"lv\": \"Mongolija\", \"pl\": \"Mongolia\", \"pt\": \"Mongólia\", \"ru\": \"Монголия\", \"uk\": \"Монголiя\"}', 96, 'MNG'),
(131, '{\"be\": \"Мантсэрат\", \"de\": \"Montserrat\", \"en\": \"Montserrat\", \"es\": \"Montserrat\", \"fr\": \"Montserrat\", \"it\": \"Montserrat\", \"ja\": \"モントセラト\", \"lt\": \"Monseratas\", \"lv\": \"Montserrata\", \"pl\": \"Montserrat\", \"pt\": \"Montserrat\", \"ru\": \"Монтсеррат\", \"uk\": \"Монтсеррат\"}', 4, 'MSR'),
(132, '{\"be\": \"М\'янма\", \"de\": \"Myanmar\", \"en\": \"Myanmar\", \"es\": \"Birmania\", \"fr\": \"Birmanie\", \"it\": \"Myanmar\", \"ja\": \"ミャンマー\", \"lt\": \"Birma\", \"lv\": \"Mjanma\", \"pl\": \"Birma\", \"pt\": \"Mianmar\", \"ru\": \"Мьянма\", \"uk\": \"М\'янма\"}', 95, 'MMR'),
(133, '{\"be\": \"Намібія\", \"de\": \"Namibia\", \"en\": \"Namibia\", \"es\": \"Namibia\", \"fr\": \"Namibie\", \"it\": \"Namibia\", \"ja\": \"ナミビア\", \"lt\": \"Namibija\", \"lv\": \"Namībija\", \"pl\": \"Namibia\", \"pt\": \"Namíbia\", \"ru\": \"Намибия\", \"uk\": \"Намiбiя\"}', 105, 'NAM'),
(134, '{\"be\": \"Навуру\", \"de\": \"Nauru\", \"en\": \"Nauru\", \"es\": \"Nauru\", \"fr\": \"Nauru\", \"it\": \"Nauru\", \"ja\": \"ナウル\", \"lt\": \"Nauru\", \"lv\": \"Nauru\", \"pl\": \"Nauru\", \"pt\": \"Nauru\", \"ru\": \"Науру\", \"uk\": \"Науру\"}', 10, 'NRU'),
(135, '{\"be\": \"Нэпал\", \"de\": \"Nepal\", \"en\": \"Nepal\", \"es\": \"Nepal\", \"fr\": \"Népal\", \"it\": \"Nepal\", \"ja\": \"ネパール\", \"lt\": \"Nepalas\", \"lv\": \"Nepāla\", \"pl\": \"Nepal\", \"pt\": \"Nepal\", \"ru\": \"Непал\", \"uk\": \"Непал\"}', 109, 'NPL'),
(136, '{\"be\": \"Нігер\", \"de\": \"Niger\", \"en\": \"Niger\", \"es\": \"Níger\", \"fr\": \"Niger\", \"it\": \"Niger\", \"ja\": \"ニジェール\", \"lt\": \"Nigeris\", \"lv\": \"Nigera\", \"pl\": \"Niger\", \"pt\": \"Níger\", \"ru\": \"Нигер\", \"uk\": \"Нiгер\"}', 16, 'NER'),
(137, '{\"be\": \"Нігерыя\", \"de\": \"Nigeria\", \"en\": \"Nigeria\", \"es\": \"Nigeria\", \"fr\": \"Nigéria\", \"it\": \"Nigeria\", \"ja\": \"ナイジェリア\", \"lt\": \"Nigerija\", \"lv\": \"Nigērija\", \"pl\": \"Nigeria\", \"pt\": \"Nigéria\", \"ru\": \"Нигерия\", \"uk\": \"Нiгерiя\"}', 107, 'NGA'),
(138, '{\"be\": \"Кюрасаа\", \"de\": \"Niederländische Antillen\", \"en\": \"Curaçao\", \"es\": \"Antillas Holandesas\", \"fr\": \"Antilles Néerlandaises\", \"it\": \"Antille Olandesi\", \"ja\": \"キュラソー島\", \"lt\": \"Kiurasao \", \"lv\": \"Nīderlandes Antilas\", \"pl\": \"Curacao\", \"pt\": \"Curaçao\", \"ru\": \"Кюрасао\", \"uk\": \"Кюрасао\"}', 41, 'CUW'),
(139, '{\"be\": \"Нідэрлянды\", \"de\": \"Niederlande\", \"en\": \"Netherlands\", \"es\": \"Holanda\", \"fr\": \"Pays-Bas\", \"it\": \"Olanda\", \"ja\": \"オランダ\", \"lt\": \"Nyderlandai\", \"lv\": \"Nīderlande\", \"pl\": \"Holandia\", \"pt\": \"Países Baixos\", \"ru\": \"Нидерланды\", \"uk\": \"Нiдерланди\"}', 1, 'NLD'),
(140, '{\"be\": \"Нікарагуа\", \"de\": \"Nicaragua\", \"en\": \"Nicaragua\", \"es\": \"Nicaragua\", \"fr\": \"Nicaragua\", \"it\": \"Nicaragua\", \"ja\": \"ニカラグア\", \"lt\": \"Nikaragva\", \"lv\": \"Nikaragva\", \"pl\": \"Nikaragua\", \"pt\": \"Nicarágua\", \"ru\": \"Никарагуа\", \"uk\": \"Нiкарагуа\"}', 108, 'NIC'),
(141, '{\"be\": \"Нівуэ\", \"de\": \"Niue\", \"en\": \"Niue\", \"es\": \"Niue\", \"fr\": \"Niue\", \"it\": \"Niue\", \"ja\": \"ニウエ\", \"lt\": \"Niue<br>\", \"lv\": \"Niue\", \"pl\": \"Niue\", \"pt\": \"Niue\", \"ru\": \"Ниуэ\", \"uk\": \"Нiуе\"}', 34, 'NIU'),
(142, '{\"be\": \"Новая Зэляндыя\", \"de\": \"Neuseeland\", \"en\": \"New Zealand\", \"es\": \"Nueva Zelanda\", \"fr\": \"Nouvelle Zélande\", \"it\": \"Nuova Zelanda\", \"ja\": \"ニュージーランド\", \"lt\": \"Naujoji Zelandija\", \"lv\": \"Jaunzelande\", \"pl\": \"Nowa Zelandia\", \"pt\": \"Nova Zelândia\", \"ru\": \"Новая Зеландия\", \"uk\": \"Нова Зеландiя\"}', 34, 'NZL'),
(143, '{\"be\": \"Новая Каледонія\", \"de\": \"Neukaledonien\", \"en\": \"New Caledonia\", \"es\": \"Nueva Caledonia\", \"fr\": \"Nouvelle Calédonie\", \"it\": \"Nuova Caledonia\", \"ja\": \"ニューカレドニア\", \"lt\": \"Naujoji Kaledonija\", \"lv\": \"Jaunkaledonija\", \"pl\": \"Nowa Kaledonia\", \"pt\": \"Nova Caledónia\", \"ru\": \"Новая Каледония\", \"uk\": \"Нова Каледонiя\"}', 106, 'NCL'),
(144, '{\"be\": \"Нарвэгія\", \"de\": \"Norwegen\", \"en\": \"Norway\", \"es\": \"Noruega\", \"fr\": \"Norvège\", \"it\": \"Norvegia\", \"ja\": \"ノルウェー\", \"lt\": \"Norvegija\", \"lv\": \"Norvēģija\", \"pl\": \"Norwegia\", \"pt\": \"Noruega\", \"ru\": \"Норвегия\", \"uk\": \"Норвегiя\"}', 26, 'NOR'),
(145, '{\"be\": \"Аб\'яднаныя Арабскія Эміраты\", \"de\": \"Vereinigte Arabische Emirate\", \"en\": \"United Arab Emirates\", \"es\": \"Emiratos Árabes Unidos\", \"fr\": \"Emirats Arabes Unis\", \"it\": \"Emirati Arabi Uniti\", \"ja\": \"アラブ首長国連邦\", \"lt\": \"Jungtiniai Arabų Emyratai\", \"lv\": \"Apvienotie Arābu Emirati\", \"pl\": \"Zjednoczone Emiraty Arabskie\", \"pt\": \"Emirados Árabes Unidos\", \"ru\": \"Объединенные Арабские Эмираты\", \"uk\": \"Об\'єднанi Арабськi Емiрати\"}', 2, 'ARE'),
(146, '{\"be\": \"Аман\", \"de\": \"Oman\", \"en\": \"Oman\", \"es\": \"Omán\", \"fr\": \"Oman\", \"it\": \"Oman\", \"ja\": \"オマーン\", \"lt\": \"Omanas\", \"lv\": \"Omāna\", \"pl\": \"Oman\", \"pt\": \"Omã\", \"ru\": \"Оман\", \"uk\": \"Оман\"}', 110, 'OMN'),
(147, '{\"be\": \"Выспа Мэн\", \"de\": \"Insel Man\", \"en\": \"Isle of Man\", \"es\": \"Islas Man\", \"fr\": \"Ile de Man\", \"it\": \"Isola di Man\", \"ja\": \"マン島\", \"lt\": \"Meno sala\", \"lv\": \"Mēna\", \"pl\": \"Isle of Man\", \"pt\": \"Ilha de Man\", \"ru\": \"Остров Мэн\", \"uk\": \"Острiв Мен\"}', 53, 'IMN'),
(148, '{\"be\": \"Выспа Норфалк\", \"de\": \"Norfolkinsel\", \"en\": \"Norfolk Island\", \"es\": \"Islas Norfolk\", \"fr\": \"Ile Norfolk\", \"it\": \"Isola di Norfolk\", \"ja\": \"ノーフォーク諸島\", \"lt\": \"Norfolko sala\", \"lv\": \"Norfolka\", \"pl\": \"Wyspa Norfolk\", \"pt\": \"Ilha Norfolk\", \"ru\": \"Остров Норфолк\", \"uk\": \"Острiв Норфолк\"}', 10, 'NFK'),
(149, '{\"be\": \"Выспы Кайман\", \"de\": \"Kaimaninseln\", \"en\": \"Cayman Islands\", \"es\": \"Islas Caimán\", \"fr\": \"Iles Caïman\", \"it\": \"Isole Cayman\", \"ja\": \"ケイマン諸島\", \"lt\": \"Kaimanų salos\", \"lv\": \"Kaimana salas\", \"pl\": \"Kajmany\", \"pt\": \"Ilhas Caimão\", \"ru\": \"Острова Кайман\", \"uk\": \"Острови Кайман\"}', 82, 'CYM'),
(150, '{\"be\": \"Выспы Кука\", \"de\": \"Cook-Inseln\", \"en\": \"Cook Islands\", \"es\": \"Islas Cook\", \"fr\": \"Iles Cook\", \"it\": \"Isole Cook\", \"ja\": \"クック諸島\", \"lt\": \"Kuko salos\", \"lv\": \"Kūka salas\", \"pl\": \"Wyspy Cooka\", \"pt\": \"Ilha Cook\", \"ru\": \"Острова Кука\", \"uk\": \"Острови Кука\"}', 34, 'COK'),
(151, '{\"be\": \"Выспы Тэркс і Кайкос\", \"de\": \"Turks- und Caicos Inseln\", \"en\": \"Turks and Caicos Islands\", \"es\": \"Islas Turcas y Caicos\", \"fr\": \"Iles Turques et Caïques\", \"it\": \"Isole Turks e Caicos\", \"ja\": \"タークス・カイコス諸島\", \"lt\": \"Terkso ir Kaikoso salos\", \"lv\": \"Tērksas un Kaikosas\", \"pl\": \"Turks i Caicos\", \"pt\": \"Ilhas Turcas e Caicos\", \"ru\": \"Острова Теркс и Кайкос\", \"uk\": \"Острови Теркс i Кайкос\"}', 9, 'TCA'),
(152, '{\"be\": \"Пакістан\", \"de\": \"Pakistan\", \"en\": \"Pakistan\", \"es\": \"Pakistán\", \"fr\": \"Pakistan\", \"it\": \"Pakistan\", \"ja\": \"パキスタン\", \"lt\": \"Pakistanas\", \"lv\": \"Pakistāna\", \"pl\": \"Pakistan\", \"pt\": \"Paquistão\", \"ru\": \"Пакистан\", \"uk\": \"Пакистан\"}', 115, 'PAK'),
(153, '{\"be\": \"Палаў\", \"de\": \"Palau\", \"en\": \"Palau\", \"es\": \"Palaos\", \"fr\": \"Palaos\", \"it\": \"Palau\", \"ja\": \"パラウ\", \"lt\": \"Palau\", \"lv\": \"Palava\", \"pl\": \"Palau\", \"pt\": \"Palau\", \"ru\": \"Палау\", \"uk\": \"Палау\"}', 9, 'PLW'),
(154, '{\"be\": \"Палестынская аўтаномія\", \"de\": \"Palestina\", \"en\": \"Palestine\", \"es\": \"Palestina\", \"fr\": \"Palestine\", \"it\": \"ANP\", \"ja\": \"パレスティナ自治区\", \"lt\": \"Palestinos teritorija\", \"lv\": \"Palestīnas autonomija\", \"pl\": \"Palestyna\", \"pt\": \"Autoridade Nacional Palestiniana\", \"ru\": \"Палестинская автономия\", \"uk\": \"Палестинська автономiя\"}', 67, 'PSE'),
(155, '{\"be\": \"Панама\", \"de\": \"Panama\", \"en\": \"Panama\", \"es\": \"Panamá\", \"fr\": \"Panama\", \"it\": \"Panama\", \"ja\": \"パナマ\", \"lt\": \"Panama\", \"lv\": \"Panama\", \"pl\": \"Panama\", \"pt\": \"Panamá\", \"ru\": \"Панама\", \"uk\": \"Панама\"}', 111, 'PAN'),
(156, '{\"be\": \"Папуа - Новая Ґвінэя\", \"de\": \"Papua-Neuguinea\", \"en\": \"Papua New Guinea\", \"es\": \"Papúa Nueva Guinea\", \"fr\": \"Papouasie-Nouvelle Guinée\", \"it\": \"Papua Nuova Guinea\", \"ja\": \"パプア・ニューギニア\", \"lt\": \"Papua - Naujoji Gvinėja\", \"lv\": \"Papua Jaungvineja\", \"pl\": \"Papua Nowa Gwinea\", \"pt\": \"Papua-Nova Guiné\", \"ru\": \"Папуа - Новая Гвинея\", \"uk\": \"Папуа - Нова Гвiнея\"}', 113, 'PNG'),
(157, '{\"be\": \"Параґвай\", \"de\": \"Paraguay\", \"en\": \"Paraguay\", \"es\": \"Paraguay\", \"fr\": \"Paraguay\", \"it\": \"Paraguay\", \"ja\": \"パラグアイ\", \"lt\": \"Paragvajus\", \"lv\": \"Paragvaja\", \"pl\": \"Paragwaj\", \"pt\": \"Paraguai\", \"ru\": \"Парагвай\", \"uk\": \"Парагвай\"}', 117, 'PRY'),
(158, '{\"be\": \"Пэру\", \"de\": \"Peru\", \"en\": \"Peru\", \"es\": \"Perú\", \"fr\": \"Pérou\", \"it\": \"Peru\", \"ja\": \"ペルー\", \"lt\": \"Peru\", \"lv\": \"Peru\", \"pl\": \"Peru\", \"pt\": \"Peru\", \"ru\": \"Перу\", \"uk\": \"Перу\"}', 112, 'PER'),
(159, '{\"be\": \"Піткэрн\", \"de\": \"Pitcairn-Inseln\", \"en\": \"Pitcairn Islands\", \"es\": \"Islas Pitcairn\", \"fr\": \"Iles Pitcairn\", \"it\": \"Isole Pitcairn\", \"ja\": \"ピトケアン諸島\", \"lt\": \"Pitkernas\", \"lv\": \"Pitkerna\", \"pl\": \"Wyspy Pitcairn\", \"pt\": \"Ilhas Pitcairn\", \"ru\": \"Питкерн\", \"uk\": \"Пiткерн\"}', 34, 'PCN'),
(160, '{\"be\": \"Польшча\", \"de\": \"Polen\", \"en\": \"Poland\", \"es\": \"Polonia\", \"fr\": \"Pologne\", \"it\": \"Polonia\", \"ja\": \"ポーランド\", \"lt\": \"Lenkija\", \"lv\": \"Polija\", \"pl\": \"Polska\", \"pt\": \"Polónia\", \"ru\": \"Польша\", \"uk\": \"Польща\"}', 116, 'POL'),
(161, '{\"be\": \"Партугалія\", \"de\": \"Portugal\", \"en\": \"Portugal\", \"es\": \"Portugal\", \"fr\": \"Portugal\", \"it\": \"Portogallo\", \"ja\": \"ポルトガル\", \"lt\": \"Portugalija\", \"lv\": \"Portugāle\", \"pl\": \"Portugalia\", \"pt\": \"Portugal\", \"ru\": \"Португалия\", \"uk\": \"Португалiя\"}', 1, 'PRT'),
(162, '{\"be\": \"Пуэрта-Рыка\", \"de\": \"Puerto Rico\", \"en\": \"Puerto Rico\", \"es\": \"Puerto Rico\", \"fr\": \"Porto Rico\", \"it\": \"Puerto Rico\", \"ja\": \"プエルトリコ\", \"lt\": \"Puerto Rikas\", \"lv\": \"Puerto Riko\", \"pl\": \"Puerto Rico\", \"pt\": \"Porto Rico\", \"ru\": \"Пуэрто-Рико\", \"uk\": \"Пуерто-Рiко\"}', 9, 'PRI'),
(163, '{\"be\": \"Рэюньён\", \"de\": \"Réunion\", \"en\": \"Réunion\", \"es\": \"Reunión\", \"fr\": \"Réunion\", \"it\": \"Réunion\", \"ja\": \"レユニオン\", \"lt\": \"Reunionas\", \"lv\": \"Reinjona\", \"pl\": \"Réunion\", \"pt\": \"Réunion\", \"ru\": \"Реюньон\", \"uk\": \"Реюньон\"}', 1, 'REU'),
(164, '{\"be\": \"Руанда\", \"de\": \"Ruanda\", \"en\": \"Rwanda\", \"es\": \"Ruanda\", \"fr\": \"Rwanda\", \"it\": \"Ruanda\", \"ja\": \"ルアンダ\", \"lt\": \"Ruanda\", \"lv\": \"Ruanda\", \"pl\": \"Rwanda\", \"pt\": \"Ruanda\", \"ru\": \"Руанда\", \"uk\": \"Руанда\"}', 122, 'RWA'),
(165, '{\"be\": \"Румынія\", \"de\": \"Rumänien\", \"en\": \"Romania\", \"es\": \"Rumanía\", \"fr\": \"Roumanie\", \"it\": \"Romania\", \"ja\": \"ルーマニア\", \"lt\": \"Rumunija\", \"lv\": \"Rumānija\", \"pl\": \"Rumunia\", \"pt\": \"Roménia\", \"ru\": \"Румыния\", \"uk\": \"Румунiя\"}', 119, 'ROU'),
(166, '{\"be\": \"Сальвадор\", \"de\": \"El Salvador\", \"en\": \"El Salvador\", \"es\": \"El Salvador\", \"fr\": \"Salvador\", \"it\": \"El Salvador\", \"ja\": \"サルバドール\", \"lt\": \"Salvadoras\", \"lv\": \"Salvadora\", \"pl\": \"Salwador\", \"pt\": \"Salvador\", \"ru\": \"Сальвадор\", \"uk\": \"Сальвадор\"}', 9, 'SLV'),
(167, '{\"be\": \"Самоа\", \"de\": \"Samoa\", \"en\": \"Samoa\", \"es\": \"Samoa\", \"fr\": \"Samoa\", \"it\": \"Samoa\", \"ja\": \"サモア\", \"lt\": \"Samoa\", \"lv\": \"Samoa\", \"pl\": \"Samoa\", \"pt\": \"Samoa\", \"ru\": \"Самоа\", \"uk\": \"Самоа\"}', 153, 'WSM'),
(168, '{\"be\": \"Сан-Марына\", \"de\": \"San Marino\", \"en\": \"San Marino\", \"es\": \"San Marino\", \"fr\": \"Saint-Marin\", \"it\": \"San Marino\", \"ja\": \"サンマリノ\", \"lt\": \"San Marinas\", \"lv\": \"Sanmarino\", \"pl\": \"San Marino\", \"pt\": \"San Marino\", \"ru\": \"Сан-Марино\", \"uk\": \"Сан-Марiно\"}', 1, 'SMR'),
(169, '{\"be\": \"Сан-Тамэ й Прынсыпі\", \"de\": \"São Tomé und Príncipe\", \"en\": \"São Tomé and Príncipe\", \"es\": \"Santo Tomé y Príncipe\", \"fr\": \"Sao-Tomé et Principe\", \"it\": \"São Tomé e Príncipe\", \"ja\": \"サントメ・プリンシペ\", \"lt\": \"San Tomė ir Prinsipė\", \"lv\": \"San Tome un Prinsipi\", \"pl\": \"Wyspy São Tomé i Książęca\", \"pt\": \"São Tomé e Príncipe\", \"ru\": \"Сан-Томе и Принсипи\", \"uk\": \"Сан-Томе i Прiнсiпi\"}', 134, 'STP'),
(170, '{\"be\": \"Саудаўская Арабія\", \"de\": \"Saudi Arabien\", \"en\": \"Saudi Arabia\", \"es\": \"Arabia Saudí\", \"fr\": \"Arabie Saoudite\", \"it\": \"Arabia Saudita\", \"ja\": \"サウジアラビア\", \"lt\": \"Saudo Arabija\", \"lv\": \"Sauda Arābija\", \"pl\": \"Arabia Saudyjska\", \"pt\": \"Arábia Saudita\", \"ru\": \"Саудовская Аравия\", \"uk\": \"Саудiвська Аравiя\"}', 123, 'SAU'),
(172, '{\"be\": \"Сьвятая Алена\", \"de\": \"St. Helena\", \"en\": \"Saint Helena\", \"es\": \"Santa Helena\", \"fr\": \"Sainte Hélène\", \"it\": \"Sant\'Elena\", \"ja\": \"セントヘレナ\", \"lt\": \"ŠV. Elenos sala\", \"lv\": \"Svētās Helēnas Sala\", \"pl\": \"Wyspa Św. Heleny\", \"pt\": \"Santa Elena\", \"ru\": \"Святая Елена\", \"uk\": \"Святої Єлени\"}', 130, 'SHN'),
(173, '{\"be\": \"Паўночная Карэя\", \"de\": \"Nordkorea\", \"en\": \"North Korea\", \"es\": \"Corea del Norte\", \"fr\": \"Corée du Nord\", \"it\": \"Corea del Nord\", \"ja\": \"北朝鮮\", \"lt\": \"Šiaurės Korėja\", \"lv\": \"Ziemeļkoreja\", \"pl\": \"Korea Północna\", \"pt\": \"Coreia do Norte\", \"ru\": \"Северная Корея\", \"uk\": \"Пiвнiчна Корея\"}', 79, 'PRK'),
(174, '{\"be\": \"Паўночныя Марыянскія выспы\", \"de\": \"Nördliche Marianen\", \"en\": \"Northern Mariana Islands\", \"es\": \"Islas Marianas del Norte\", \"fr\": \"Iles Mariannes du Nord\", \"it\": \"Isole Northern Mariana\", \"ja\": \"北マリアナ諸島\", \"lt\": \"Šiaurės Marianų salos\", \"lv\": \"Ziemeļu Marianas Salas\", \"pl\": \"Wyspy Północnej Mariany\", \"pt\": \"Ilhas Marianas\", \"ru\": \"Северные Марианские острова\", \"uk\": \"Пiвнiчнi Марiанськi острови\"}', 9, 'MNP'),
(175, '{\"be\": \"Сэйшэлы\", \"de\": \"Seychellen\", \"en\": \"Seychelles\", \"es\": \"Seychelles\", \"fr\": \"Seychelles\", \"it\": \"Seychelles\", \"ja\": \"セイシェル\", \"lt\": \"Seišeliai\", \"lv\": \"Seišelu salas\", \"pl\": \"Seszele\", \"pt\": \"Seicheles\", \"ru\": \"Сейшелы\", \"uk\": \"Сейшели\"}', 125, 'SYC'),
(176, '{\"be\": \"Сэнэгал\", \"de\": \"Senegal\", \"en\": \"Senegal\", \"es\": \"Senegal\", \"fr\": \"Sénégal\", \"it\": \"Senegal\", \"ja\": \"セネガル\", \"lt\": \"Senegalas<br>\", \"lv\": \"Senegāla\", \"pl\": \"Senegal\", \"pt\": \"Senegal\", \"ru\": \"Сенегал\", \"uk\": \"Сенегал\"}', 16, 'SEN'),
(177, '{\"be\": \"Сэнт-Вінцэнт\", \"de\": \"St. Vincent und die Grenadinen\", \"en\": \"Saint Vincent and the Grenadines\", \"es\": \"San Vicente\", \"fr\": \"Saint-Vincent\", \"it\": \"Saint Vincent e Grenadines\", \"ja\": \"セントビンセント\", \"lt\": \"Sent Vincentas\", \"lv\": \"Sentvinsenta\", \"pl\": \"Saint Vincent i Grenadyny\", \"pt\": \"São Vicente\", \"ru\": \"Сент-Винсент\", \"uk\": \"Сент-Вiнсент\"}', 4, 'VCT'),
(178, '{\"be\": \"Сэнт-Кітз і Нэвіс\", \"de\": \"St. Kitts und Nevis\", \"en\": \"Saint Kitts and Nevis\", \"es\": \"San Cristóbal y Nieves\", \"fr\": \"Saint-Christophe et Niévès\", \"it\": \"Saint Kitts e Nevis\", \"ja\": \"クリストファー・ネイビス\", \"lt\": \"Sent Kitsas ir Nevis\", \"lv\": \"Sentkitsa un Nevisa\", \"pl\": \"Saint Kitts i Nevis\", \"pt\": \"São Cristóvão e Nevis\", \"ru\": \"Сент-Китс и Невис\", \"uk\": \"Сент-Китс i Невiс\"}', 4, 'KNA'),
(179, '{\"be\": \"Сэнт-Люсія\", \"de\": \"St. Lucia\", \"en\": \"Saint Lucia\", \"es\": \"Santa Lucía\", \"fr\": \"Sainte-Lucie\", \"it\": \"Saint Lucia\", \"ja\": \"セントルシア\", \"lt\": \"Sent Liucija\", \"lv\": \"Sentlusija\", \"pl\": \"Santa Lucia\", \"pt\": \"Santa Lúcia\", \"ru\": \"Сент-Люсия\", \"uk\": \"Сент-Люсiя\"}', 4, 'LCA'),
(180, '{\"be\": \"Сэн-П’ер і Мікелён\", \"de\": \"Saint-Pierre und Miquelon\", \"en\": \"Saint Pierre and Miquelon\", \"es\": \"San Pedro y Miguelón\", \"fr\": \"Saint-Pierre et Miquelon\", \"it\": \"Saint Pierre e Miquelon\", \"ja\": \"サンピエール島・ミクロン島\", \"lt\": \"Sen Pjeras ir Mikelonas\", \"lv\": \"Senpjēra un Mikelona\", \"pl\": \"Saint Pierre i Miquelon\", \"pt\": \"Saint-Pierre e Miquelon\", \"ru\": \"Сент-Пьер и Микелон\", \"uk\": \"Сент-Пьєр i Мiкелон\"}', 1, 'SPM'),
(181, '{\"be\": \"Сэрбія\", \"de\": \"Serbien\", \"en\": \"Serbia\", \"es\": \"Serbia\", \"fr\": \"Serbie\", \"it\": \"Serbia\", \"ja\": \"セルビア\", \"lt\": \"Serbija\", \"lv\": \"Serbija\", \"pl\": \"Serbia\", \"pt\": \"Sérvia\", \"ru\": \"Сербия\", \"uk\": \"Сербiя\"}', 120, 'SRB'),
(182, '{\"be\": \"Сынґапур\", \"de\": \"Singapur\", \"en\": \"Singapore\", \"es\": \"Singapur\", \"fr\": \"Singapour\", \"it\": \"Singapore\", \"ja\": \"シンガポール\", \"lt\": \"Singapūras\", \"lv\": \"Singapūra\", \"pl\": \"Singapur\", \"pt\": \"Singapura\", \"ru\": \"Сингапур\", \"uk\": \"Сiнгапур\"}', 129, 'SGP'),
(183, '{\"be\": \"Сырыйская Арабская Рэспубліка\", \"de\": \"Syrien\", \"en\": \"Syria\", \"es\": \"Siria\", \"fr\": \"Syrie\", \"it\": \"Siria\", \"ja\": \"シリア・アラブ共和国\", \"lt\": \"Sirija\", \"lv\": \"Sīrija\", \"pl\": \"Syria\", \"pt\": \"República Árabe da Síria\", \"ru\": \"Сирийская Арабская Республика\", \"uk\": \"Сiрiйська Арабська Республiка\"}', 135, 'SYR'),
(184, '{\"be\": \"Славаччына\", \"de\": \"Slowakei\", \"en\": \"Slovakia\", \"es\": \"Eslovaquia\", \"fr\": \"Slovaquie\", \"it\": \"Slovacchia\", \"ja\": \"スロヴァキア\", \"lt\": \"Slovakija\", \"lv\": \"Slovākija\", \"pl\": \"Słowacja\", \"pt\": \"Eslováquia\", \"ru\": \"Словакия\", \"uk\": \"Словаччина\"}', 1, 'SVK'),
(185, '{\"be\": \"Славенія\", \"de\": \"Slowenien\", \"en\": \"Slovenia\", \"es\": \"Eslovenia\", \"fr\": \"Slovénie\", \"it\": \"Slovenia\", \"ja\": \"スロベニア\", \"lt\": \"Slovėnija\", \"lv\": \"Slovēnija\", \"pl\": \"Słowenia\", \"pt\": \"Eslovénia\", \"ru\": \"Словения\", \"uk\": \"Словенiя\"}', 1, 'SVN'),
(186, '{\"be\": \"Саламонавы выспы\", \"de\": \"Salomoninseln\", \"en\": \"Solomon Islands\", \"es\": \"Islas Salomón\", \"fr\": \"Iles Salomon\", \"it\": \"Isole Solomon\", \"ja\": \"ソロモン諸島\", \"lt\": \"Saliamono salos\", \"lv\": \"Zālamanu salas\", \"pl\": \"Wyspy Solomona\", \"pt\": \"Ilhas Salomão\", \"ru\": \"Соломоновы Острова\", \"uk\": \"Соломоновi Острови\"}', 124, 'SLB'),
(187, '{\"be\": \"Самалі\", \"de\": \"Somalia\", \"en\": \"Somalia\", \"es\": \"Somalia\", \"fr\": \"Somalie\", \"it\": \"Somalia\", \"ja\": \"ソマリア\", \"lt\": \"Somalis\", \"lv\": \"Somali\", \"pl\": \"Somalia\", \"pt\": \"Somália\", \"ru\": \"Сомали\", \"uk\": \"Сомалi\"}', 132, 'SOM'),
(188, '{\"be\": \"Судан\", \"de\": \"Sudan\", \"en\": \"Sudan\", \"es\": \"Sudán\", \"fr\": \"Soudan\", \"it\": \"Sudan\", \"ja\": \"スーダン\", \"lt\": \"Sudanas\", \"lv\": \"Sudāna\", \"pl\": \"Sudan\", \"pt\": \"Sudão\", \"ru\": \"Судан\", \"uk\": \"Судан\"}', 126, 'SDN'),
(189, '{\"be\": \"Сурынам\", \"de\": \"Suriname\", \"en\": \"Suriname\", \"es\": \"Surinam\", \"fr\": \"Surinam\", \"it\": \"Suriname\", \"ja\": \"スリナム\", \"lt\": \"Surinamas\", \"lv\": \"Surinama\", \"pl\": \"Surinam\", \"pt\": \"Suriname\", \"ru\": \"Суринам\", \"uk\": \"Сурiнам\"}', 133, 'SUR'),
(190, '{\"be\": \"Сьера-Леонэ\", \"de\": \"Sierra Leone\", \"en\": \"Sierra Leone\", \"es\": \"Sierra Leona\", \"fr\": \"Sierra Leone\", \"it\": \"Sierra Leone\", \"ja\": \"シエラレオネ\", \"lt\": \"Siera Leonė\", \"lv\": \"Sjero-Leone\", \"pl\": \"Sierra Leone\", \"pt\": \"Serra Leoa\", \"ru\": \"Сьерра-Леоне\", \"uk\": \"Сьєрра-Леоне\"}', 131, 'SLE'),
(191, '{\"be\": \"Тайлянд\", \"de\": \"Thailand\", \"en\": \"Thailand\", \"es\": \"Tailandia\", \"fr\": \"Thaïlande\", \"it\": \"Tailandia\", \"ja\": \"タイ\", \"lt\": \"Tailandas\", \"lv\": \"Tailanda\", \"pl\": \"Tajlandia\", \"pt\": \"Tailândia\", \"ru\": \"Таиланд\", \"uk\": \"Таїланд\"}', 137, 'THA'),
(192, '{\"be\": \"Тайвань\", \"de\": \"Taiwan\", \"en\": \"Taiwan\", \"es\": \"Taiwan\", \"fr\": \"Taïwan\", \"it\": \"Taiwan\", \"ja\": \"台湾\", \"lt\": \"Taivanas\", \"lv\": \"Taivāna\", \"pl\": \"Tajwan\", \"pt\": \"Taiwan\", \"ru\": \"Тайвань\", \"uk\": \"Тайвань\"}', 144, 'TWN'),
(193, '{\"be\": \"Танзанія\", \"de\": \"Tansania\", \"en\": \"Tanzania\", \"es\": \"Tanzania\", \"fr\": \"Tanzanie\", \"it\": \"Tanzania\", \"ja\": \"タンザニア\", \"lt\": \"Tanzanija\", \"lv\": \"Tanzānija\", \"pl\": \"Tanzania\", \"pt\": \"Tanzânia\", \"ru\": \"Танзания\", \"uk\": \"Танзанiя\"}', 145, 'TZA'),
(194, '{\"be\": \"Тога\", \"de\": \"Togo\", \"en\": \"Togo\", \"es\": \"Togo\", \"fr\": \"Togo\", \"it\": \"Togo\", \"ja\": \"トーゴ\", \"lt\": \"Togas\", \"lv\": \"Togo\", \"pl\": \"Togo\", \"pt\": \"Togo\", \"ru\": \"Того\", \"uk\": \"Того\"}', 16, 'TGO'),
(195, '{\"be\": \"Такелаў\", \"de\": \"Tokelau\", \"en\": \"Tokelau\", \"es\": \"Tokelau\", \"fr\": \"Tokelau\", \"it\": \"Tokelau\", \"ja\": \"トケラウ\", \"lt\": \"Tokelau\", \"lv\": \"Tokelava\", \"pl\": \"Tokelau\", \"pt\": \"Tokelau\", \"ru\": \"Токелау\", \"uk\": \"Токелау\"}', 34, 'TKL'),
(196, '{\"be\": \"Тонга\", \"de\": \"Tonga\", \"en\": \"Tonga\", \"es\": \"Tonga\", \"fr\": \"Tonga\", \"it\": \"Tonga\", \"ja\": \"トンガ\", \"lt\": \"Tongas\", \"lv\": \"Tonga\", \"pl\": \"Tonga\", \"pt\": \"Tonga\", \"ru\": \"Тонга\", \"uk\": \"Тонга\"}', 141, 'TON'),
(197, '{\"be\": \"Трынідад і Табага\", \"de\": \"Trinidad und Tobago\", \"en\": \"Trinidad and Tobago\", \"es\": \"Trinidad y Tobago\", \"fr\": \"Trinité et Tobago\", \"it\": \"Trinidad e Tobago\", \"ja\": \"トリニダード・トバゴ\", \"lt\": \"Trinidadas ir Tobagas\", \"lv\": \"Trindada un Tobago\", \"pl\": \"Trinidad and Tobago\", \"pt\": \"Trinidad e Tobago\", \"ru\": \"Тринидад и Тобаго\", \"uk\": \"Тринiдад i Тобаго\"}', 143, 'TTO'),
(198, '{\"be\": \"Тувалу\", \"de\": \"Tuvalu\", \"en\": \"Tuvalu\", \"es\": \"Tuvalu\", \"fr\": \"Tuvalu\", \"it\": \"Tuvalu\", \"ja\": \"ツバル\", \"lt\": \"Tuvalu\", \"lv\": \"Tuvalu\", \"pl\": \"Tuvalu\", \"pt\": \"Tuvalu\", \"ru\": \"Тувалу\", \"uk\": \"Тувалу\"}', 10, 'TUV'),
(199, '{\"be\": \"Туніс\", \"de\": \"Tunesien\", \"en\": \"Tunisia\", \"es\": \"Túnez\", \"fr\": \"Tunisie\", \"it\": \"Tunisia\", \"ja\": \"チュニス\", \"lt\": \"Tunisas\", \"lv\": \"Tunisija\", \"pl\": \"Tunezja\", \"pt\": \"Tunísia\", \"ru\": \"Тунис\", \"uk\": \"Тунiс\"}', 140, 'TUN'),
(200, '{\"be\": \"Турэччына\", \"de\": \"Türkei\", \"en\": \"Turkey\", \"es\": \"Turquía\", \"fr\": \"Turquie\", \"it\": \"Turchia\", \"ja\": \"トルコ\", \"lt\": \"Turkija\", \"lv\": \"Turcija\", \"pl\": \"Turcja\", \"pt\": \"Turquia\", \"ru\": \"Турция\", \"uk\": \"Туреччина\"}', 142, 'TUR'),
(201, '{\"be\": \"Уганда\", \"de\": \"Uganda\", \"en\": \"Uganda\", \"es\": \"Uganda\", \"fr\": \"Ouganda\", \"it\": \"Uganda\", \"ja\": \"ウガンダ\", \"lt\": \"Uganda\", \"lv\": \"Uganda\", \"pl\": \"Uganda\", \"pt\": \"Uganda\", \"ru\": \"Уганда\", \"uk\": \"Уганда\"}', 147, 'UGA'),
(202, '{\"be\": \"Ўоліс і Футуна\", \"de\": \"Wallis und Futuna\", \"en\": \"Wallis and Futuna\", \"es\": \"Wallis y Futuna\", \"fr\": \"Wallis et Futuna\", \"it\": \"Wallis e Futuna\", \"ja\": \"ウォリス・フツナ\", \"lt\": \"Vallisas ir Futuna\", \"lv\": \"Volisa un Futuna\", \"pl\": \"Wallis i Futuna\", \"pt\": \"Wallis e Futuna\", \"ru\": \"Уоллис и Футуна\", \"uk\": \"Уоллiс i Футуна\"}', 106, 'WLF'),
(203, '{\"be\": \"Уруґвай\", \"de\": \"Uruguay\", \"en\": \"Uruguay\", \"es\": \"Uruguay\", \"fr\": \"Uruguay\", \"it\": \"Uruguay\", \"ja\": \"ウルグアイ\", \"lt\": \"Urugvajus\", \"lv\": \"Urugvaja\", \"pl\": \"Urugwaj\", \"pt\": \"Uruguai\", \"ru\": \"Уругвай\", \"uk\": \"Уругвай\"}', 148, 'URY'),
(204, '{\"be\": \"Фарэрскія выспы\", \"de\": \"Färöer\", \"en\": \"Faroe Islands\", \"es\": \"Islas Feroe\", \"fr\": \"Iles Féroé\", \"it\": \"Isole Faroe\", \"ja\": \"フェロー諸島\", \"lt\": \"Farerų salos\", \"lv\": \"Fāru salas\", \"pl\": \"Wyspy Owcze\", \"pt\": \"Ilhas Feroe\", \"ru\": \"Фарерские острова\", \"uk\": \"Фарерськi острови\"}', 44, 'FRO'),
(205, '{\"be\": \"Фіджы\", \"de\": \"Fidschi\", \"en\": \"Fiji\", \"es\": \"Fiyi\", \"fr\": \"Fidji\", \"it\": \"Fiji\", \"ja\": \"フィジー\", \"lt\": \"Fidžis\", \"lv\": \"Fidži\", \"pl\": \"Fidżi\", \"pt\": \"Fiji\", \"ru\": \"Фиджи\", \"uk\": \"Фiджi\"}', 51, 'FJI'),
(206, '{\"be\": \"Філіпіны\", \"de\": \"Philippinen\", \"en\": \"Philippines\", \"es\": \"Filipinas\", \"fr\": \"Philippines\", \"it\": \"Filippine\", \"ja\": \"フィリピン\", \"lt\": \"Filipinai\", \"lv\": \"Filipīnas\", \"pl\": \"Filipiny\", \"pt\": \"Filipinas\", \"ru\": \"Филиппины\", \"uk\": \"Фiлiппiни\"}', 114, 'PHL'),
(207, '{\"be\": \"Фінляндыя\", \"de\": \"Finnland\", \"en\": \"Finland\", \"es\": \"Finlandia\", \"fr\": \"Finlande\", \"it\": \"Finlandia\", \"ja\": \"フィンランド\", \"lt\": \"Suomija\", \"lv\": \"Somija\", \"pl\": \"Finlandia\", \"pt\": \"Finlândia\", \"ru\": \"Финляндия\", \"uk\": \"Фiнляндiя\"}', 1, 'FIN'),
(208, '{\"be\": \"Фальклэндзкія выспы\", \"de\": \"Falkland Inseln\", \"en\": \"Falkland Islands\", \"es\": \"Islas Malvinas\", \"fr\": \"Iles Malouines\", \"it\": \"Isole Falkland/Malvinas\", \"ja\": \"フォークランド諸島\", \"lt\": \"Folklendų salos\", \"lv\": \"Folklendas salas\", \"pl\": \"Wyspy Falklandzkie\", \"pt\": \"Ilhas Malvinas\", \"ru\": \"Фолклендские острова\", \"uk\": \"Фолклендськi острови\"}', 52, 'FLK'),
(209, '{\"be\": \"Францыя\", \"de\": \"Frankreich\", \"en\": \"France\", \"es\": \"Francia\", \"fr\": \"France\", \"it\": \"Francia\", \"ja\": \"フランス\", \"lt\": \"Prancūzija\", \"lv\": \"Francija\", \"pl\": \"Francja\", \"pt\": \"França\", \"ru\": \"Франция\", \"uk\": \"Францiя\"}', 1, 'FRA'),
(210, '{\"be\": \"Француская Гвіяна\", \"de\": \"Französisch-Guayana\", \"en\": \"French Guiana\", \"es\": \"Guayana Francesa\", \"fr\": \"Guyane française\", \"it\": \"Guiana Francese\", \"ja\": \"フランス領ガイアナ\", \"lt\": \"Prancūzijos Gviana\", \"lv\": \"Franču Gviāna\", \"pl\": \"Gujana Francuska\", \"pt\": \"Guiana Francesa\", \"ru\": \"Французская Гвиана\", \"uk\": \"Французька Гвiана\"}', 1, 'GUF'),
(211, '{\"be\": \"Француская Палінэзія\", \"de\": \"Französisch Polynesien\", \"en\": \"French Polynesia\", \"es\": \"Polinesia Francesa\", \"fr\": \"Polynésie française\", \"it\": \"Polinesia Francese\", \"ja\": \"フランス領ポリネシア\", \"lt\": \"Prancūzijos Polinezija\", \"lv\": \"Fraņču Polinēzija\", \"pl\": \"Polinezja Francuska\", \"pt\": \"Polinésia Francesa\", \"ru\": \"Французская Полинезия\", \"uk\": \"Французька Полiнезiя\"}', 106, 'PYF'),
(212, '{\"be\": \"Харватыя\", \"de\": \"Kroatien\", \"en\": \"Croatia\", \"es\": \"Croacia\", \"fr\": \"Croatie\", \"it\": \"Croazia\", \"ja\": \"クロアチア\", \"lt\": \"Kroatija\", \"lv\": \"Horvātija\", \"pl\": \"Chorwacja\", \"pt\": \"Croácia\", \"ru\": \"Хорватия\", \"uk\": \"Хорватiя\"}', 63, 'HRV'),
(213, '{\"be\": \"Цэнтральна-Афрыканская Рэспубліка\", \"de\": \"Zentralafrikanische Republik\", \"en\": \"Central African Republic\", \"es\": \"República Centroafricana\", \"fr\": \"République centrafricaine\", \"it\": \"Repubblica Centro Africana\", \"ja\": \"中央アフリカ共和国\", \"lt\": \"Centrinės Afrikos Respublika\", \"lv\": \"Centrālāfrikas Republika\", \"pl\": \"Republika Środkowo-Afrykańska\", \"pt\": \"República Centro-Africana\", \"ru\": \"Центрально-Африканская Республика\", \"uk\": \"Центральноафриканська Республiка\"}', 32, 'CAF'),
(214, '{\"be\": \"Чад\", \"de\": \"Tschad\", \"en\": \"Chad\", \"es\": \"Chad\", \"fr\": \"Tchad\", \"it\": \"Chad\", \"ja\": \"チャド\", \"lt\": \"Čadas\", \"lv\": \"Čada\", \"pl\": \"Czad\", \"pt\": \"Chade\", \"ru\": \"Чад\", \"uk\": \"Чад\"}', 32, 'TCD'),
(215, '{\"be\": \"Чэхія\", \"de\": \"Tschechische Republik\", \"en\": \"Czech Republic\", \"es\": \"Chequia\", \"fr\": \"République tchèque\", \"it\": \"Repubblica Ceca\", \"ja\": \"チェコ\", \"lt\": \"Čekija\", \"lv\": \"Čehija\", \"pl\": \"Czechy\", \"pt\": \"República Checa\", \"ru\": \"Чехия\", \"uk\": \"Чехiя\"}', 42, 'CZE'),
(216, '{\"be\": \"Чылі\", \"de\": \"Chile\", \"en\": \"Chile\", \"es\": \"Chile\", \"fr\": \"Chili\", \"it\": \"Cile\", \"ja\": \"チリ\", \"lt\": \"Čilė\", \"lv\": \"Čīle\", \"pl\": \"Chile\", \"pt\": \"Chile\", \"ru\": \"Чили\", \"uk\": \"Чилi\"}', 35, 'CHL'),
(217, '{\"be\": \"Швайцарыя\", \"de\": \"Schweiz\", \"en\": \"Switzerland\", \"es\": \"Suiza\", \"fr\": \"Suisse\", \"it\": \"Svizzera\", \"ja\": \"スイス\", \"lt\": \"Šveicarija\", \"lv\": \"Šveice\", \"pl\": \"Szwajcaria\", \"pt\": \"Suíça\", \"ru\": \"Швейцария\", \"uk\": \"Швейцарiя\"}', 33, 'CHE'),
(218, '{\"be\": \"Швэцыя\", \"de\": \"Schweden\", \"en\": \"Sweden\", \"es\": \"Suecia\", \"fr\": \"Suède\", \"it\": \"Svezia\", \"ja\": \"スウェーデン\", \"lt\": \"Švedija\", \"lv\": \"Zviedrija\", \"pl\": \"Szwecja\", \"pt\": \"Suécia\", \"ru\": \"Швеция\", \"uk\": \"Швецiя\"}', 128, 'SWE'),
(219, '{\"be\": \"Шпіцбэрґен і Ян Маен\", \"de\": \"Svalbard und Jan Mayen\", \"en\": \"Svalbard and Jan Mayen\", \"es\": \"Spitsbergen y Jan Mayen\", \"fr\": \"Spitzberg et Jan Mayen\", \"it\": \"Svalbard e Jan Mayen\", \"ja\": \"スピッツベルゲン島・ヤンマイエン島\", \"lt\": \"Svalbardo ir Jan Majen salos\", \"lv\": \"Špicbergena\", \"pl\": \"Svalbard i Jan Mayen\", \"pt\": \"Spitsbergen  e Jan Mayen\", \"ru\": \"Шпицберген и Ян Майен\", \"uk\": \"Шпiцберген i Ян Майен\"}', 26, 'SJM'),
(220, '{\"be\": \"Шры-Лянка\", \"de\": \"Sri Lanka\", \"en\": \"Sri Lanka\", \"es\": \"Sri-Lanka\", \"fr\": \"Sri Lanka\", \"it\": \"Sri Lanka\", \"ja\": \"スリランカ\", \"lt\": \"Šri Lanka\", \"lv\": \"Šrilanka\", \"pl\": \"Sri Lanka\", \"pt\": \"Sri Lanka\", \"ru\": \"Шри-Ланка\", \"uk\": \"Шрi-Ланка\"}', 86, 'LKA'),
(221, '{\"be\": \"Эквадор\", \"de\": \"Ecuador\", \"en\": \"Ecuador\", \"es\": \"Ecuador\", \"fr\": \"Equateur\", \"it\": \"Ecuador\", \"ja\": \"エクアドル\", \"lt\": \"Ekvadoras\", \"lv\": \"Ekvadora\", \"pl\": \"Ekwador\", \"pt\": \"Equador\", \"ru\": \"Эквадор\", \"uk\": \"Еквадор\"}', 9, 'ECU'),
(222, '{\"be\": \"Экватарыяльная Гвінэя\", \"de\": \"Äquatorialguinea\", \"en\": \"Equatorial Guinea\", \"es\": \"Guinea Ecuatorial\", \"fr\": \"Guinée équatoriale\", \"it\": \"Guinea Equatoriale\", \"ja\": \"赤道ギニア\", \"lt\": \"Pusiaujo Gvinėja\", \"lv\": \"Ekvotoriāla Gvineja\", \"pl\": \"Gwinea Równikowa\", \"pt\": \"Guiné Equatorial\", \"ru\": \"Экваториальная Гвинея\", \"uk\": \"Екваторiальна Гвiнея\"}', 32, 'GNQ'),
(223, '{\"be\": \"Эрытрэя\", \"de\": \"Eritrea\", \"en\": \"Eritrea\", \"es\": \"Eritrea\", \"fr\": \"Erythrée\", \"it\": \"Eritrea\", \"ja\": \"エリトリア\", \"lt\": \"Eritrėja\", \"lv\": \"Eritreja\", \"pl\": \"Erytrea\", \"pt\": \"Eritreia\", \"ru\": \"Эритрея\", \"uk\": \"Ерiтрея\"}', 49, 'ERI'),
(224, '{\"be\": \"Этыёпія\", \"de\": \"Äthiopien\", \"en\": \"Ethiopia\", \"es\": \"Etiopía\", \"fr\": \"Ethiopie\", \"it\": \"Etiopia\", \"ja\": \"エチオピア\", \"lt\": \"Etiopija\", \"lv\": \"Etiopija\", \"pl\": \"Etiopia\", \"pt\": \"Etiópia\", \"ru\": \"Эфиопия\", \"uk\": \"Ефiопiя\"}', 50, 'ETH'),
(225, '{\"be\": \"Паўднёвая Карэя\", \"de\": \"Südkorea\", \"en\": \"South Korea\", \"es\": \"Corea del Sur\", \"fr\": \"Corée du Sud\", \"it\": \"Corea del Sud\", \"ja\": \"大韓民国\", \"lt\": \"Pietų Korėja\", \"lv\": \"Dienvidkoreja\", \"pl\": \"Korea Południowa\", \"pt\": \"Coreia do Sul\", \"ru\": \"Южная Корея\", \"uk\": \"Пiвденна Корея\"}', 80, 'KOR'),
(226, '{\"be\": \"Паўднёва-Афрыканская Рэспубліка\", \"de\": \"Südafrika\", \"en\": \"South Africa\", \"es\": \"República de Sudáfrica\", \"fr\": \"Afrique du Sud\", \"it\": \"Sud Africa\", \"ja\": \"南アフリカ共和国\", \"lt\": \"Pietų Afrikos Respublika\", \"lv\": \"Dienvidāfrikas Republika\", \"pl\": \"RPA\", \"pt\": \"República da África do Sul\", \"ru\": \"Южно-Африканская Республика\", \"uk\": \"Пiвденно-Африканська Республiка\"}', 155, 'ZAF'),
(227, '{\"be\": \"Ямайка\", \"de\": \"Jamaika\", \"en\": \"Jamaica\", \"es\": \"Jamaica\", \"fr\": \"Jamaïque\", \"it\": \"Giamaica\", \"ja\": \"ジャマイカ\", \"lt\": \"Jamaika\", \"lv\": \"Jamaika\", \"pl\": \"Jamajka\", \"pt\": \"Jamaica\", \"ru\": \"Ямайка\", \"uk\": \"Ямайка\"}', 72, 'JAM'),
(228, '{\"be\": \"Японія\", \"de\": \"Japan\", \"en\": \"Japan\", \"es\": \"Japón\", \"fr\": \"Japon\", \"it\": \"Giappone\", \"ja\": \"日本\", \"lt\": \"Japonija\", \"lv\": \"Japāna\", \"pl\": \"Japonia\", \"pt\": \"Japão\", \"ru\": \"Япония\", \"uk\": \"Японiя\"}', 74, 'JPN'),
(229, '{\"be\": \"Чарнагорыя\", \"de\": \"Montenegro\", \"en\": \"Montenegro\", \"es\": \"Montenegro\", \"fr\": \"Monténégro\", \"it\": \"Montenegro\", \"ja\": \"モンテネグロ\", \"lt\": \"Juodkalnija\", \"lv\": \"Melnkalne\", \"pl\": \"Czarnogóra\", \"pt\": \"Montenegro\", \"ru\": \"Черногория\", \"uk\": \"Чорногорiя\"}', 1, 'MNE'),
(230, '{\"be\": \"Джыбуці\", \"de\": \"Djibouti\", \"en\": \"Djibouti\", \"es\": \"Yibuti\", \"fr\": \"Djibouti\", \"it\": \"Djibouti\", \"ja\": \"ジブチ\", \"lt\": \"Džibutis\", \"lv\": \"Džibuti\", \"pl\": \"Dżibuti\", \"pt\": \"Djibouti\", \"ru\": \"Джибути\", \"uk\": \"Джiбутi\"}', 43, 'DJI'),
(231, '{\"be\": \"Паўднёвы Судан\", \"de\": \"Republik Südsudan\", \"en\": \"South Sudan\", \"es\": \"Sudán del Sur\", \"fr\": \"République du Soudan du Sud\", \"it\": \"Repubblica del Sudan del Sud\", \"ja\": \"南スーダン\", \"lt\": \"Pietų Sudanas \", \"lv\": \"South Sudan\", \"pl\": \"Sudan Południowy\", \"pt\": \"Sudão do Sul\", \"ru\": \"Южный Судан\", \"uk\": \"Південний Судан\"}', 127, 'SSD'),
(232, '{\"be\": \"Ватыкан\", \"de\": \"Vatikan\", \"en\": \"Vatican\", \"es\": \"Vaticano\", \"fr\": \"Vatican\", \"it\": \"Vaticano\", \"ja\": \"ヴァチカン\", \"lt\": \"Vatikanas\", \"lv\": \"Vatican\", \"pl\": \"Watykan\", \"pt\": \"Vaticano\", \"ru\": \"Ватикан\", \"uk\": \"Ватикан\"}', 1, 'VAT'),
(233, '{\"be\": \"Сінт-Мартэн\", \"de\": \"Sint Maarten\", \"en\": \"Sint Maarten\", \"es\": \"Sint Maarten\", \"fr\": \"Saint-Martin\", \"it\": \"Sint Maarten\", \"ja\": \"シント・マールテン\", \"lt\": \"Sint Martenas \", \"lv\": \"Sint Maarten\", \"pl\": \"Saint Martin\", \"pt\": \"São Martinho (Caraíbas)\", \"ru\": \"Синт-Мартен\", \"uk\": \"Сінт-Мартен\"}', 41, 'SXM')"
            );
        } catch (\Exception $exception) {
            dd($exception);
        }
    }
}
