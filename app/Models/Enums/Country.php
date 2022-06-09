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

    #[Description("Абхазия")]
    const AB = 1;

    #[Description("Австралия")]
    const AU = 2;

    #[Description("Австрия")]
    const AT = 3;

    #[Description("Азербайджан")]
    const AZ = 4;

    #[Description("Албания")]
    const AL = 5;

    #[Description("Алжир")]
    const DZ = 6;

    #[Description("Американское Самоа")]
    const AS = 7;

    #[Description("Ангилья")]
    const AI = 8;

    #[Description("Ангола")]
    const AO = 9;

    #[Description("Андорра")]
    const AD = 10;

    #[Description("Антарктида")]
    const AQ = 11;

    #[Description("Антигуа и Барбуда")]
    const AG = 12;

    #[Description("Аргентина")]
    const AR = 13;

    #[Description("Армения")]
    const AM = 14;

    #[Description("Аруба")]
    const AW = 15;

    #[Description("Афганистан")]
    const AF = 16;

    #[Description("Багамы")]
    const BS = 17;

    #[Description("Бангладеш")]
    const BD = 18;

    #[Description("Барбадос")]
    const BB = 19;

    #[Description("Бахрейн")]
    const BH = 20;

    #[Description("Беларусь")]
    const BY = 21;

    #[Description("Белиз")]
    const BZ = 22;

    #[Description("Бельгия")]
    const BE = 23;

    #[Description("Бенин")]
    const BJ = 24;

    #[Description("Бермуды")]
    const BM = 25;

    #[Description("Болгария")]
    const BG = 26;

    #[Description("Боливия, Многонациональное Государство")]
    const BO = 27;

    #[Description("Бонайре, Саба и Синт-Эстатиус")]
    const BQ = 28;

    #[Description("Босния и Герцеговина")]
    const BA = 29;

    #[Description("Ботсвана")]
    const BW = 30;

    #[Description("Бразилия")]
    const BR = 31;

    #[Description("Британская территория в Индийском океане")]
    const IO = 32;

    #[Description("Бруней-Даруссалам")]
    const BN = 33;

    #[Description("Буркина-Фасо")]
    const BF = 34;

    #[Description("Бурунди")]
    const BI = 35;

    #[Description("Бутан")]
    const BT = 36;

    #[Description("Вануату")]
    const VU = 37;

    #[Description("Венгрия")]
    const HU = 38;

    #[Description("Венесуэла Боливарианская Республика")]
    const VE = 39;

    #[Description("Виргинские острова, Британские")]
    const VG = 40;

    #[Description("Виргинские острова, США")]
    const VI = 41;

    #[Description("Вьетнам")]
    const VN = 42;

    #[Description("Габон")]
    const GA = 43;

    #[Description("Гаити")]
    const HT = 44;

    #[Description("Гайана")]
    const GY = 45;

    #[Description("Гамбия")]
    const GM = 46;

    #[Description("Гана")]
    const GH = 47;

    #[Description("Гваделупа")]
    const GP = 48;

    #[Description("Гватемала")]
    const GT = 49;

    #[Description("Гвинея")]
    const GN = 50;

    #[Description("Гвинея-Бисау")]
    const GW = 51;

    #[Description("Германия")]
    const DE = 52;

    #[Description("Гернси")]
    const GG = 53;

    #[Description("Гибралтар")]
    const GI = 54;

    #[Description("Гондурас")]
    const HN = 55;

    #[Description("Гонконг")]
    const HK = 56;

    #[Description("Гренада")]
    const GD = 57;

    #[Description("Гренландия")]
    const GL = 58;

    #[Description("Греция")]
    const GR = 59;

    #[Description("Грузия")]
    const GE = 60;

    #[Description("Гуам")]
    const GU = 61;

    #[Description("Дания")]
    const DK = 62;

    #[Description("Джерси")]
    const JE = 63;

    #[Description("Джибути")]
    const DJ = 64;

    #[Description("Доминика")]
    const DM = 65;

    #[Description("Доминиканская Республика")]
    const DO = 66;

    #[Description("Египет")]
    const EG = 67;

    #[Description("Замбия")]
    const ZM = 68;

    #[Description("Западная Сахара")]
    const EH = 69;

    #[Description("Зимбабве")]
    const ZW = 70;

    #[Description("Израиль")]
    const IL = 71;

    #[Description("Индия")]
    const IN = 72;

    #[Description("Индонезия")]
    const ID = 73;

    #[Description("Иордания")]
    const JO = 74;

    #[Description("Ирак")]
    const IQ = 75;

    #[Description("Иран, Исламская Республика")]
    const IR = 76;

    #[Description("Ирландия")]
    const IE = 77;

    #[Description("Исландия")]
    const IS = 78;

    #[Description("Испания")]
    const ES = 79;

    #[Description("Италия")]
    const IT = 80;

    #[Description("Йемен")]
    const YE = 81;

    #[Description("Кабо-Верде")]
    const CV = 82;

    #[Description("Казахстан")]
    const KZ = 83;

    #[Description("Камбоджа")]
    const KH = 84;

    #[Description("Камерун")]
    const CM = 85;

    #[Description("Канада")]
    const CA = 86;

    #[Description("Катар")]
    const QA = 87;

    #[Description("Кения")]
    const KE = 88;

    #[Description("Кипр")]
    const CY = 89;

    #[Description("Киргизия")]
    const KG = 90;

    #[Description("Кирибати")]
    const KI = 91;

    #[Description("Китай")]
    const CN = 92;

    #[Description("Кокосовые (Килинг) острова")]
    const CC = 93;

    #[Description("Колумбия")]
    const CO = 94;

    #[Description("Коморы")]
    const KM = 95;

    #[Description("Конго")]
    const CG = 96;

    #[Description("Конго, Демократическая Республика")]
    const CD = 97;

    #[Description("Корея, Народно-Демократическая Республика")]
    const KP = 98;

    #[Description("Корея, Республика")]
    const KR = 99;

    #[Description("Коста-Рика")]
    const CR = 100;

    #[Description("Кот д'Ивуар")]
    const CI = 101;

    #[Description("Куба")]
    const CU = 102;

    #[Description("Кувейт")]
    const KW = 103;

    #[Description("Кюрасао")]
    const CW = 104;

    #[Description("Лаос")]
    const LA = 105;

    #[Description("Латвия")]
    const LV = 106;

    #[Description("Лесото")]
    const LS = 107;

    #[Description("Ливан")]
    const LB = 108;

    #[Description("Ливийская Арабская Джамахирия")]
    const LY = 109;

    #[Description("Либерия")]
    const LR = 110;

    #[Description("Лихтенштейн")]
    const LI = 111;

    #[Description("Литва")]
    const LT = 112;

    #[Description("Люксембург")]
    const LU = 113;

    #[Description("Маврикий")]
    const MU = 114;

    #[Description("Мавритания")]
    const MR = 115;

    #[Description("Мадагаскар")]
    const MG = 116;

    #[Description("Майотта")]
    const YT = 117;

    #[Description("Макао")]
    const MO = 118;

    #[Description("Малави")]
    const MW = 119;

    #[Description("Малайзия")]
    const MY = 120;

    #[Description("Мали")]
    const ML = 121;

    #[Description("Малые Тихоокеанские отдаленные острова Соединенных Штатов")]
    const UM = 122;

    #[Description("Мальдивы")]
    const MV = 123;

    #[Description("Мальта")]
    const MT = 124;

    #[Description("Марокко")]
    const MA = 125;

    #[Description("Мартиника")]
    const MQ = 126;

    #[Description("Маршалловы острова")]
    const MH = 127;

    #[Description("Мексика")]
    const MX = 128;

    #[Description("Микронезия, Федеративные Штаты")]
    const FM = 129;

    #[Description("Мозамбик")]
    const MZ = 130;

    #[Description("Молдова, Республика")]
    const MD = 131;

    #[Description("Монако")]
    const MC = 132;

    #[Description("Монголия")]
    const MN = 133;

    #[Description("Монтсеррат")]
    const MS = 134;

    #[Description("Мьянма")]
    const MM = 135;

    #[Description("Намибия")]
    const NA = 136;

    #[Description("Науру")]
    const NR = 137;

    #[Description("Непал")]
    const NP = 138;

    #[Description("Нигер")]
    const NE = 139;

    #[Description("Нигерия")]
    const NG = 140;

    #[Description("Нидерланды")]
    const NL = 141;

    #[Description("Никарагуа")]
    const NI = 142;

    #[Description("Ниуэ")]
    const NU = 143;

    #[Description("Новая Зеландия")]
    const NZ = 144;

    #[Description("Новая Каледония")]
    const NC = 145;

    #[Description("Норвегия")]
    const NO = 146;

    #[Description("Объединенные Арабские Эмираты")]
    const AE = 147;

    #[Description("Оман")]
    const OM = 148;

    #[Description("Остров Буве")]
    const BV = 149;

    #[Description("Остров Мэн")]
    const IM = 150;

    #[Description("Остров Норфолк")]
    const NF = 151;

    #[Description("Остров Рождества")]
    const CX = 152;

    #[Description("Остров Херд и острова Макдональд")]
    const HM = 153;

    #[Description("Острова Кайман")]
    const KY = 154;

    #[Description("Острова Кука")]
    const CK = 155;

    #[Description("Острова Теркс и Кайкос")]
    const TC = 156;

    #[Description("Пакистан")]
    const PK = 157;

    #[Description("Палау")]
    const PW = 158;

    #[Description("Палестинская территория, оккупированная")]
    const PS = 159;

    #[Description("Панама")]
    const PA = 160;

    #[Description("Папский Престол (Государство — город Ватикан)")]
    const VA = 161;

    #[Description("Папуа-Новая Гвинея")]
    const PG = 162;

    #[Description("Парагвай")]
    const PY = 163;

    #[Description("Перу")]
    const PE = 164;

    #[Description("Питкерн")]
    const PN = 165;

    #[Description("Польша")]
    const PL = 166;

    #[Description("Португалия")]
    const PT = 167;

    #[Description("Пуэрто-Рико")]
    const PR = 168;

    #[Description("Республика Македония")]
    const MK = 169;

    #[Description("Реюньон")]
    const RE = 170;

    #[Description("Россия")]
    const RU = 171;

    #[Description("Руанда")]
    const RW = 172;

    #[Description("Румыния")]
    const RO = 173;

    #[Description("Самоа")]
    const WS = 174;

    #[Description("Сан-Марино")]
    const SM = 175;

    #[Description("Сан-Томе и Принсипи")]
    const ST = 176;

    #[Description("Саудовская Аравия")]
    const SA = 177;

    #[Description("Святая Елена, Остров вознесения, Тристан-да-Кунья")]
    const SH = 178;

    #[Description("Северные Марианские острова")]
    const MP = 179;

    #[Description("Сен-Бартельми")]
    const BL = 180;

    #[Description("Сен-Мартен")]
    const MF = 181;

    #[Description("Сенегал")]
    const SN = 182;

    #[Description("Сент-Винсент и Гренадины")]
    const VC = 183;

    #[Description("Сент-Люсия")]
    const LC = 184;

    #[Description("Сент-Китс и Невис")]
    const KN = 185;

    #[Description("Сент-Пьер и Микелон")]
    const PM = 186;

    #[Description("Сербия")]
    const RS = 187;

    #[Description("Сейшелы")]
    const SC = 188;

    #[Description("Сингапур")]
    const SG = 189;

    #[Description("Синт-Мартен")]
    const SX = 190;

    #[Description("Сирийская Арабская Республика")]
    const SY = 191;

    #[Description("Словакия")]
    const SK = 192;

    #[Description("Словения")]
    const SI = 193;

    #[Description("Соединенное Королевство")]
    const GB = 194;

    #[Description("Соединенные Штаты")]
    const US = 195;

    #[Description("Соломоновы острова")]
    const SB = 196;

    #[Description("Сомали")]
    const SO = 197;

    #[Description("Судан")]
    const SD = 198;

    #[Description("Суринам")]
    const SR = 199;

    #[Description("Сьерра-Леоне")]
    const SL = 200;

    #[Description("Таджикистан")]
    const TJ = 201;

    #[Description("Таиланд")]
    const TH = 202;

    #[Description("Тайвань (Китай)")]
    const TW = 203;

    #[Description("Танзания, Объединенная Республика")]
    const TZ = 204;

    #[Description("Тимор-Лесте")]
    const TL = 205;

    #[Description("Того")]
    const TG = 206;

    #[Description("Токелау")]
    const TK = 207;

    #[Description("Тонга")]
    const TO = 208;

    #[Description("Тринидад и Тобаго")]
    const TT = 209;

    #[Description("Тувалу")]
    const TV = 210;

    #[Description("Тунис")]
    const TN = 211;

    #[Description("Туркмения")]
    const TM = 212;

    #[Description("Турция")]
    const TR = 213;

    #[Description("Уганда")]
    const UG = 214;

    #[Description("Узбекистан")]
    const UZ = 215;

    #[Description("Украина")]
    const UA = 216;

    #[Description("Уоллис и Футуна")]
    const WF = 217;

    #[Description("Уругвай")]
    const UY = 218;

    #[Description("Фарерские острова")]
    const FO = 219;

    #[Description("Фиджи")]
    const FJ = 220;

    #[Description("Филиппины")]
    const PH = 221;

    #[Description("Финляндия")]
    const FI = 222;

    #[Description("Фолклендские острова (Мальвинские)")]
    const FK = 223;

    #[Description("Франция")]
    const FR = 224;

    #[Description("Французская Гвиана")]
    const GF = 225;

    #[Description("Французская Полинезия")]
    const PF = 226;

    #[Description("Французские Южные территории")]
    const TF = 227;

    #[Description("Хорватия")]
    const HR = 228;

    #[Description("Центрально-Африканская Республика")]
    const CF = 229;

    #[Description("Чад")]
    const TD = 230;

    #[Description("Черногория")]
    const ME = 231;

    #[Description("Чешская Республика")]
    const CZ = 232;

    #[Description("Чили")]
    const CL = 233;

    #[Description("Швейцария")]
    const CH = 234;

    #[Description("Швеция")]
    const SE = 235;

    #[Description("Шпицберген и Ян-Майен")]
    const SJ = 236;

    #[Description("Шри-Ланка")]
    const LK = 237;

    #[Description("Эквадор")]
    const EC = 238;

    #[Description("Экваториальная Гвинея")]
    const GQ = 239;

    #[Description("Эландские острова")]
    const AX = 240;

    #[Description("Эль-Сальвадор")]
    const SV = 241;

    #[Description("Эритрея")]
    const ER = 242;

    #[Description("Эсватини")]
    const SZ = 243;

    #[Description("Эстония")]
    const EE = 244;

    #[Description("Эфиопия")]
    const ET = 245;

    #[Description("Южная Африка")]
    const ZA = 246;

    #[Description("Южная Джорджия и Южные Сандвичевы острова")]
    const GS = 247;

    #[Description("Южная Осетия")]
    const OS = 248;

    #[Description("Южный Судан")]
    const SS = 249;

    #[Description("Ямайка")]
    const JM = 250;

    #[Description("Япония")]
    const JP = 251;

}
