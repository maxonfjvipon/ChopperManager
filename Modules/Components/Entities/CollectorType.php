<?php

namespace Modules\Components\Entities;

use App\Traits\EnumHelpers;
use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;
use Modules\Selection\Entities\StationType;

/**
 * Collector type.
 *
 * @method static getDescriptions()
 */
final class CollectorType extends Enum
{
    use EnumHelpers;

    #[Description('Общий')]
    public const Common = 1;

    #[Description('Раздельный')]
    public const Separated = 2;

    public static function getTypeByStationType(string $station_type): int
    {
        return match ($station_type) {
            StationType::getKey(StationType::WS) => self::Common,
            StationType::getKey(StationType::AF) => self::Separated
        };
    }
}
