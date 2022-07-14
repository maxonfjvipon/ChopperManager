<?php

namespace Modules\Project\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * Project status.
 */
final class ProjectStatus extends Enum
{
    use EnumHelpers;

    #[Description('Проект')]
    public const Project = 1;

    #[Description('Цикл 0')]
    public const Phase0 = 2;

    #[Description('Цикл крыша')]
    public const PhaseRoof = 3;

    #[Description('Архив')]
    public const Archive = 4;
}
