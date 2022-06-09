<?php

namespace Modules\Components\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Armature type
 */
final class ArmatureType extends Enum
{
    use EnumHelpers;

    #[Description("Быстросъемное соединение")]
    const AM = 1;

    #[Description("Затвор")]
    const ZF = 2;

    #[Description("Кран")]
    const KR = 3;

    #[Description("Ниппель")]
    const NR = 4;

    #[Description("Обратный клапан")]
    const OKF = 5;

    #[Description("Фланец с переходом на резьбу")]
    const FNR = 6;

    #[Description("Катушка")]
    const KatF = 7;

    #[Description("Катушка с патрубком")]
    const KatP = 8; // todo change in xlsx from Kat+P

    #[Description("Комплект резьбового тройника")]
    const KRT = 9;
}
