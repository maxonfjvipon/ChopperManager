<?php

namespace App\Models\Enums;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Country.
 */
final class Country extends Enum
{
    use EnumHelpers;

    #[Description('Абхазия')]
    public const AB = 1;

    #[Description('Австралия')]
    public const AU = 2;

    #[Description('Австрия')]
    public const AT = 3;

    #[Description('Азербайджан')]
    public const AZ = 4;

    #[Description('Албания')]
    public const AL = 5;

    #[Description('Алжир')]
    public const DZ = 6;

    #[Description('Американское Самоа')]
    public const AS = 7;

    #[Description('Ангилья')]
    public const AI = 8;

    #[Description('Ангола')]
    public const AO = 9;

    #[Description('Андорра')]
    public const AD = 10;

    #[Description('Антарктида')]
    public const AQ = 11;

    #[Description('Антигуа и Барбуда')]
    public const AG = 12;

    #[Description('Аргентина')]
    public const AR = 13;

    #[Description('Армения')]
    public const AM = 14;

    #[Description('Аруба')]
    public const AW = 15;

    #[Description('Афганистан')]
    public const AF = 16;

    #[Description('Багамы')]
    public const BS = 17;

    #[Description('Бангладеш')]
    public const BD = 18;

    #[Description('Барбадос')]
    public const BB = 19;

    #[Description('Бахрейн')]
    public const BH = 20;

    #[Description('Беларусь')]
    public const BY = 21;

    #[Description('Белиз')]
    public const BZ = 22;

    #[Description('Бельгия')]
    public const BE = 23;

    #[Description('Бенин')]
    public const BJ = 24;

    #[Description('Бермуды')]
    public const BM = 25;

    #[Description('Болгария')]
    public const BG = 26;

    #[Description('Боливия, Многонациональное Государство')]
    public const BO = 27;

    #[Description('Бонайре, Саба и Синт-Эстатиус')]
    public const BQ = 28;

    #[Description('Босния и Герцеговина')]
    public const BA = 29;

    #[Description('Ботсвана')]
    public const BW = 30;

    #[Description('Бразилия')]
    public const BR = 31;

    #[Description('Британская территория в Индийском океане')]
    public const IO = 32;

    #[Description('Бруней-Даруссалам')]
    public const BN = 33;

    #[Description('Буркина-Фасо')]
    public const BF = 34;

    #[Description('Бурунди')]
    public const BI = 35;

    #[Description('Бутан')]
    public const BT = 36;

    #[Description('Вануату')]
    public const VU = 37;

    #[Description('Венгрия')]
    public const HU = 38;

    #[Description('Венесуэла Боливарианская Республика')]
    public const VE = 39;

    #[Description('Виргинские острова, Британские')]
    public const VG = 40;

    #[Description('Виргинские острова, США')]
    public const VI = 41;

    #[Description('Вьетнам')]
    public const VN = 42;

    #[Description('Габон')]
    public const GA = 43;

    #[Description('Гаити')]
    public const HT = 44;

    #[Description('Гайана')]
    public const GY = 45;

    #[Description('Гамбия')]
    public const GM = 46;

    #[Description('Гана')]
    public const GH = 47;

    #[Description('Гваделупа')]
    public const GP = 48;

    #[Description('Гватемала')]
    public const GT = 49;

    #[Description('Гвинея')]
    public const GN = 50;

    #[Description('Гвинея-Бисау')]
    public const GW = 51;

    #[Description('Германия')]
    public const DE = 52;

    #[Description('Гернси')]
    public const GG = 53;

    #[Description('Гибралтар')]
    public const GI = 54;

    #[Description('Гондурас')]
    public const HN = 55;

    #[Description('Гонконг')]
    public const HK = 56;

    #[Description('Гренада')]
    public const GD = 57;

    #[Description('Гренландия')]
    public const GL = 58;

    #[Description('Греция')]
    public const GR = 59;

    #[Description('Грузия')]
    public const GE = 60;

    #[Description('Гуам')]
    public const GU = 61;

    #[Description('Дания')]
    public const DK = 62;

    #[Description('Джерси')]
    public const JE = 63;

    #[Description('Джибути')]
    public const DJ = 64;

    #[Description('Доминика')]
    public const DM = 65;

    #[Description('Доминиканская Республика')]
    public const DO = 66;

    #[Description('Египет')]
    public const EG = 67;

    #[Description('Замбия')]
    public const ZM = 68;

    #[Description('Западная Сахара')]
    public const EH = 69;

    #[Description('Зимбабве')]
    public const ZW = 70;

    #[Description('Израиль')]
    public const IL = 71;

    #[Description('Индия')]
    public const IN = 72;

    #[Description('Индонезия')]
    public const ID = 73;

    #[Description('Иордания')]
    public const JO = 74;

    #[Description('Ирак')]
    public const IQ = 75;

    #[Description('Иран, Исламская Республика')]
    public const IR = 76;

    #[Description('Ирландия')]
    public const IE = 77;

    #[Description('Исландия')]
    public const IS = 78;

    #[Description('Испания')]
    public const ES = 79;

    #[Description('Италия')]
    public const IT = 80;

    #[Description('Йемен')]
    public const YE = 81;

    #[Description('Кабо-Верде')]
    public const CV = 82;

    #[Description('Казахстан')]
    public const KZ = 83;

    #[Description('Камбоджа')]
    public const KH = 84;

    #[Description('Камерун')]
    public const CM = 85;

    #[Description('Канада')]
    public const CA = 86;

    #[Description('Катар')]
    public const QA = 87;

    #[Description('Кения')]
    public const KE = 88;

    #[Description('Кипр')]
    public const CY = 89;

    #[Description('Киргизия')]
    public const KG = 90;

    #[Description('Кирибати')]
    public const KI = 91;

    #[Description('Китай')]
    public const CN = 92;

    #[Description('Кокосовые (Килинг) острова')]
    public const CC = 93;

    #[Description('Колумбия')]
    public const CO = 94;

    #[Description('Коморы')]
    public const KM = 95;

    #[Description('Конго')]
    public const CG = 96;

    #[Description('Конго, Демократическая Республика')]
    public const CD = 97;

    #[Description('Корея, Народно-Демократическая Республика')]
    public const KP = 98;

    #[Description('Корея, Республика')]
    public const KR = 99;

    #[Description('Коста-Рика')]
    public const CR = 100;

    #[Description("Кот д'Ивуар")]
    public const CI = 101;

    #[Description('Куба')]
    public const CU = 102;

    #[Description('Кувейт')]
    public const KW = 103;

    #[Description('Кюрасао')]
    public const CW = 104;

    #[Description('Лаос')]
    public const LA = 105;

    #[Description('Латвия')]
    public const LV = 106;

    #[Description('Лесото')]
    public const LS = 107;

    #[Description('Ливан')]
    public const LB = 108;

    #[Description('Ливийская Арабская Джамахирия')]
    public const LY = 109;

    #[Description('Либерия')]
    public const LR = 110;

    #[Description('Лихтенштейн')]
    public const LI = 111;

    #[Description('Литва')]
    public const LT = 112;

    #[Description('Люксембург')]
    public const LU = 113;

    #[Description('Маврикий')]
    public const MU = 114;

    #[Description('Мавритания')]
    public const MR = 115;

    #[Description('Мадагаскар')]
    public const MG = 116;

    #[Description('Майотта')]
    public const YT = 117;

    #[Description('Макао')]
    public const MO = 118;

    #[Description('Малави')]
    public const MW = 119;

    #[Description('Малайзия')]
    public const MY = 120;

    #[Description('Мали')]
    public const ML = 121;

    #[Description('Малые Тихоокеанские отдаленные острова Соединенных Штатов')]
    public const UM = 122;

    #[Description('Мальдивы')]
    public const MV = 123;

    #[Description('Мальта')]
    public const MT = 124;

    #[Description('Марокко')]
    public const MA = 125;

    #[Description('Мартиника')]
    public const MQ = 126;

    #[Description('Маршалловы острова')]
    public const MH = 127;

    #[Description('Мексика')]
    public const MX = 128;

    #[Description('Микронезия, Федеративные Штаты')]
    public const FM = 129;

    #[Description('Мозамбик')]
    public const MZ = 130;

    #[Description('Молдова, Республика')]
    public const MD = 131;

    #[Description('Монако')]
    public const MC = 132;

    #[Description('Монголия')]
    public const MN = 133;

    #[Description('Монтсеррат')]
    public const MS = 134;

    #[Description('Мьянма')]
    public const MM = 135;

    #[Description('Намибия')]
    public const NA = 136;

    #[Description('Науру')]
    public const NR = 137;

    #[Description('Непал')]
    public const NP = 138;

    #[Description('Нигер')]
    public const NE = 139;

    #[Description('Нигерия')]
    public const NG = 140;

    #[Description('Нидерланды')]
    public const NL = 141;

    #[Description('Никарагуа')]
    public const NI = 142;

    #[Description('Ниуэ')]
    public const NU = 143;

    #[Description('Новая Зеландия')]
    public const NZ = 144;

    #[Description('Новая Каледония')]
    public const NC = 145;

    #[Description('Норвегия')]
    public const NO = 146;

    #[Description('Объединенные Арабские Эмираты')]
    public const AE = 147;

    #[Description('Оман')]
    public const OM = 148;

    #[Description('Остров Буве')]
    public const BV = 149;

    #[Description('Остров Мэн')]
    public const IM = 150;

    #[Description('Остров Норфолк')]
    public const NF = 151;

    #[Description('Остров Рождества')]
    public const CX = 152;

    #[Description('Остров Херд и острова Макдональд')]
    public const HM = 153;

    #[Description('Острова Кайман')]
    public const KY = 154;

    #[Description('Острова Кука')]
    public const CK = 155;

    #[Description('Острова Теркс и Кайкос')]
    public const TC = 156;

    #[Description('Пакистан')]
    public const PK = 157;

    #[Description('Палау')]
    public const PW = 158;

    #[Description('Палестинская территория, оккупированная')]
    public const PS = 159;

    #[Description('Панама')]
    public const PA = 160;

    #[Description('Папский Престол (Государство — город Ватикан)')]
    public const VA = 161;

    #[Description('Папуа-Новая Гвинея')]
    public const PG = 162;

    #[Description('Парагвай')]
    public const PY = 163;

    #[Description('Перу')]
    public const PE = 164;

    #[Description('Питкерн')]
    public const PN = 165;

    #[Description('Польша')]
    public const PL = 166;

    #[Description('Португалия')]
    public const PT = 167;

    #[Description('Пуэрто-Рико')]
    public const PR = 168;

    #[Description('Республика Македония')]
    public const MK = 169;

    #[Description('Реюньон')]
    public const RE = 170;

    #[Description('Россия')]
    public const RU = 171;

    #[Description('Руанда')]
    public const RW = 172;

    #[Description('Румыния')]
    public const RO = 173;

    #[Description('Самоа')]
    public const WS = 174;

    #[Description('Сан-Марино')]
    public const SM = 175;

    #[Description('Сан-Томе и Принсипи')]
    public const ST = 176;

    #[Description('Саудовская Аравия')]
    public const SA = 177;

    #[Description('Святая Елена, Остров вознесения, Тристан-да-Кунья')]
    public const SH = 178;

    #[Description('Северные Марианские острова')]
    public const MP = 179;

    #[Description('Сен-Бартельми')]
    public const BL = 180;

    #[Description('Сен-Мартен')]
    public const MF = 181;

    #[Description('Сенегал')]
    public const SN = 182;

    #[Description('Сент-Винсент и Гренадины')]
    public const VC = 183;

    #[Description('Сент-Люсия')]
    public const LC = 184;

    #[Description('Сент-Китс и Невис')]
    public const KN = 185;

    #[Description('Сент-Пьер и Микелон')]
    public const PM = 186;

    #[Description('Сербия')]
    public const RS = 187;

    #[Description('Сейшелы')]
    public const SC = 188;

    #[Description('Сингапур')]
    public const SG = 189;

    #[Description('Синт-Мартен')]
    public const SX = 190;

    #[Description('Сирийская Арабская Республика')]
    public const SY = 191;

    #[Description('Словакия')]
    public const SK = 192;

    #[Description('Словения')]
    public const SI = 193;

    #[Description('Соединенное Королевство')]
    public const GB = 194;

    #[Description('Соединенные Штаты')]
    public const US = 195;

    #[Description('Соломоновы острова')]
    public const SB = 196;

    #[Description('Сомали')]
    public const SO = 197;

    #[Description('Судан')]
    public const SD = 198;

    #[Description('Суринам')]
    public const SR = 199;

    #[Description('Сьерра-Леоне')]
    public const SL = 200;

    #[Description('Таджикистан')]
    public const TJ = 201;

    #[Description('Таиланд')]
    public const TH = 202;

    #[Description('Тайвань (Китай)')]
    public const TW = 203;

    #[Description('Танзания, Объединенная Республика')]
    public const TZ = 204;

    #[Description('Тимор-Лесте')]
    public const TL = 205;

    #[Description('Того')]
    public const TG = 206;

    #[Description('Токелау')]
    public const TK = 207;

    #[Description('Тонга')]
    public const TO = 208;

    #[Description('Тринидад и Тобаго')]
    public const TT = 209;

    #[Description('Тувалу')]
    public const TV = 210;

    #[Description('Тунис')]
    public const TN = 211;

    #[Description('Туркмения')]
    public const TM = 212;

    #[Description('Турция')]
    public const TR = 213;

    #[Description('Уганда')]
    public const UG = 214;

    #[Description('Узбекистан')]
    public const UZ = 215;

    #[Description('Украина')]
    public const UA = 216;

    #[Description('Уоллис и Футуна')]
    public const WF = 217;

    #[Description('Уругвай')]
    public const UY = 218;

    #[Description('Фарерские острова')]
    public const FO = 219;

    #[Description('Фиджи')]
    public const FJ = 220;

    #[Description('Филиппины')]
    public const PH = 221;

    #[Description('Финляндия')]
    public const FI = 222;

    #[Description('Фолклендские острова (Мальвинские)')]
    public const FK = 223;

    #[Description('Франция')]
    public const FR = 224;

    #[Description('Французская Гвиана')]
    public const GF = 225;

    #[Description('Французская Полинезия')]
    public const PF = 226;

    #[Description('Французские Южные территории')]
    public const TF = 227;

    #[Description('Хорватия')]
    public const HR = 228;

    #[Description('Центрально-Африканская Республика')]
    public const CF = 229;

    #[Description('Чад')]
    public const TD = 230;

    #[Description('Черногория')]
    public const ME = 231;

    #[Description('Чешская Республика')]
    public const CZ = 232;

    #[Description('Чили')]
    public const CL = 233;

    #[Description('Швейцария')]
    public const CH = 234;

    #[Description('Швеция')]
    public const SE = 235;

    #[Description('Шпицберген и Ян-Майен')]
    public const SJ = 236;

    #[Description('Шри-Ланка')]
    public const LK = 237;

    #[Description('Эквадор')]
    public const EC = 238;

    #[Description('Экваториальная Гвинея')]
    public const GQ = 239;

    #[Description('Эландские острова')]
    public const AX = 240;

    #[Description('Эль-Сальвадор')]
    public const SV = 241;

    #[Description('Эритрея')]
    public const ER = 242;

    #[Description('Эсватини')]
    public const SZ = 243;

    #[Description('Эстония')]
    public const EE = 244;

    #[Description('Эфиопия')]
    public const ET = 245;

    #[Description('Южная Африка')]
    public const ZA = 246;

    #[Description('Южная Джорджия и Южные Сандвичевы острова')]
    public const GS = 247;

    #[Description('Южная Осетия')]
    public const OS = 248;

    #[Description('Южный Судан')]
    public const SS = 249;

    #[Description('Ямайка')]
    public const JM = 250;

    #[Description('Япония')]
    public const JP = 251;
}
