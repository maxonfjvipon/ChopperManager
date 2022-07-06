<?php

namespace Modules\ProjectParticipant\Entities;

use App\Traits\Cached;
use App\Traits\HasArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Area;

/**
 * Contractor.
 *
 * @property int $id
 * @property string $name
 * @property string $itn
 * @property Area $area
 * @property string $full_name
 *
 * @method static self firstOrCreate(array $find, array $attributes)
 */
final class Contractor extends Model
{
    const SEPARATOR = "?";

    use HasFactory, Cached, HasArea;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return "contractors";
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return implode(" / ", [$this->name, $this->itn, $this->area->name]);
    }

    /**
     * @param string|null $contractorToCreate
     * @param string $separator
     * @return Contractor|null
     */
    public static function getOrCreateFrom(?string $contractorToCreate, string $separator = "?"): ?self
    {
        return !!$contractorToCreate
            ? self::firstOrCreate([
                'itn' => ($elems = explode($separator, $contractorToCreate))[1],
                'area_id' => $areaId = Area::getByRegionKladrId($elems[2])->id
            ], [
                'name' => $elems[0],
                'itn' => $elems[1],
                'area_id' => $areaId
            ])
            : null;
    }
}
