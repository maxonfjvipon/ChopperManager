<?php

namespace Modules\User\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Area.
 *
 * @property int $id
 * @property string $name
 * @property int $region_kladr_id
 */
final class Area extends Model
{
    use HasFactory, Cached;

    public $timestamps = false;
    protected $guarded = [];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return 'areas';
    }

    /**
     * @param string $regionKladrId
     * @return self
     */
    public static function getByRegionKladrId(string $regionKladrId): self
    {
        return self::firstWhere('region_kladr_id', (int)substr($regionKladrId, 0, 2));
    }

    /**
     * @return string
     */
    public function fullRegionKladrId(): string
    {
        return ($this->region_kladr_id < 10 ? "0" : "") . $this->region_kladr_id . "00000000000";
    }
}
