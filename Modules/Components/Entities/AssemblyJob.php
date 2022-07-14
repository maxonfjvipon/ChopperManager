<?php

namespace Modules\Components\Entities;

use App\Models\Enums\Currency;
use App\Traits\Cached;
use App\Traits\HasPriceByRates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Assembly job.
 *
 * @property ControlSystemType $control_system_type
 */
final class AssemblyJob extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Cached;
    use HasPriceByRates;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'currency' => Currency::class,
        'collector_type' => CollectorType::class,
    ];

    protected static function getCacheKey(): string
    {
        return 'assembly_jobs';
    }

    public function control_system_type(): BelongsTo
    {
        return $this->belongsTo(ControlSystemType::class, 'control_system_type_id');
    }
}
