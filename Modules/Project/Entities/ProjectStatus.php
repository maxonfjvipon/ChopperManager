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

    #[Description("Проект")]
    const Project = 1;

    #[Description("Цикл 0")]
    const Phase0 = 2;

    #[Description("Цикл крыша")]
    const PhaseRoof = 3;

    #[Description("Архив")]
    const Archive = 4;
}
