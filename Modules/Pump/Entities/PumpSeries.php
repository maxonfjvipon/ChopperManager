<?php

namespace Modules\Pump\Entities;

use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Http\Requests\PumpSeriesUpdateRequest;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasDiscount;
use Modules\Pump\Traits\PumpSeries\PumpSeriesRelationships;
use Modules\Pump\Traits\PumpSeries\PumpSeriesAttributes;
use Modules\Pump\Traits\PumpSeries\PumpSeriesScopes;

/**
 * Pump series.
 * @property mixed|string $image
 * @property mixed $applications
 */
final class PumpSeries extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, HasDiscount;
    use PumpSeriesAttributes, PumpSeriesRelationships, PumpSeriesScopes;

    protected $guarded = [];
    public $timestamps = false;
    protected $softCascade = ['pumps'];

    protected $casts = [
        'is_discontinued' => 'boolean'
    ];

    public static function createFromRequest(PumpSeriesStoreRequest $request): self
    {
        $series = self::create($request->seriesFields());
        if ($series) {
            PumpSeriesAndType::createForSeries($series, $request->types);
            PumpSeriesAndApplication::createForSeries($series, $request->applications);
        }
        return $series;
    }

    public function updateFromRequest(PumpSeriesUpdateRequest $request): bool
    {
        $updated = $this->update($request->seriesFields());
        if ($updated) {
            PumpSeriesAndType::updateForSeries($this, $request->types);
            PumpSeriesAndApplication::updateForSeries($this, $request->applications);
        }
        return $updated;
    }
}
