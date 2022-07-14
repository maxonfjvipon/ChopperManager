<?php

namespace Modules\Components\Entities;

use BenSampo\Enum\Enum;

final class ComponentType extends Enum
{
    public const ControlSystems = 'control-systems';

    public const Armature = 'armature';

    public const Chassis = 'chassis';

    public const Collectors = 'collectors';

    public const AssemblyJobs = 'assembly-jobs';
}
