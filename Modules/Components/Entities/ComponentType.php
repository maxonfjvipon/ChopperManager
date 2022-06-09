<?php

namespace Modules\Components\Entities;

use BenSampo\Enum\Enum;

final class ComponentType extends Enum
{
    const ControlSystems = "control-systems";
    const Armature = "armature";
    const Chassis = "chassis";
    const Collectors = "collectors";
    const AssemblyJobs = "assembly-jobs";
}
