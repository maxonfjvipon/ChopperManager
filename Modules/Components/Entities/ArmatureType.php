<?php

namespace Modules\Components\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Armature type.
 */
final class ArmatureType extends Enum
{
    use EnumHelpers;

    #[Description('Быстросъемное соединение')]
    public const AM = 1;

    #[Description('Затвор')]
    public const ZF = 2;

    #[Description('Кран')]
    public const KR = 3;

    #[Description('Ниппель')]
    public const NR = 4;

    #[Description('Обратный клапан')]
    public const OKF = 5;

    #[Description('Фланец с переходом на резьбу')]
    public const FNR = 6;

    #[Description('Катушка')]
    public const KatF = 7;

    #[Description('Катушка с патрубком')]
    public const KatP = 8; // todo change in xlsx from Kat+P

    #[Description('Комплект резьбового тройника')]
    public const KRT = 9;
}
