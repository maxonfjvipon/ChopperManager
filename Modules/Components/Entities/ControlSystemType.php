<?php

namespace Modules\Components\Entities;

use App\Traits\Cached;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Selection\Entities\StationType;

/**
 * Control system type.
 *
 * @property string $name
 */
final class ControlSystemType extends Model
{
    use HasFactory, Cached;

    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'station_type' => StationType::class,
    ];

    /**
     * @return string
     */
    protected static function getCacheKey(): string
    {
        return 'control_system_types';
    }

    public function scopeOnStationType($query, StationType $stationType) {
        return $query->where('station_type', $stationType->value);
    }
}
