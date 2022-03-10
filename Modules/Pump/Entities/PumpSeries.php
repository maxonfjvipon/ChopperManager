<?php

namespace Modules\Pump\Entities;

use Exception;
use Modules\Pump\Database\factories\PumpSeriesFactory;
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
 *
 * @property string $image
 * @property mixed $applications
 * @property int $id
 * @property PumpBrand $brand
 * @property string $name
 * @property PumpCategory $category
 * @property ElPowerAdjustment $power_adjustment
 * @property string $imploded_applications
 * @property string $imploded_types
 * @property bool $is_discontinued
 * @property int $brand_id
 * @property int $power_adjustment_id
 * @property int $category_id
 *
 * @method static int count()
 * @method static self create(array $attributes)
 */
final class PumpSeries extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, HasDiscount;
    use PumpSeriesAttributes, PumpSeriesRelationships, PumpSeriesScopes;

    protected $guarded = [];
    public $timestamps = false;
    protected array $softCascade = ['pumps'];

    protected $casts = [
        'is_discontinued' => 'boolean'
    ];

    protected static function newFactory(): PumpSeriesFactory
    {
        return PumpSeriesFactory::new();
    }

    public static function createFromRequest(PumpSeriesStoreRequest $request): self
    {
        $series = self::create($request->seriesFields());
        if ($series) {
            PumpSeriesAndType::createForSeries($series, $request->types);
            PumpSeriesAndApplication::createForSeries($series, $request->applications);
        }
        return $series;
    }

    /**
     * @param PumpSeriesUpdateRequest $request
     * @return bool
     * @throws Exception
     */
    public function updateFromRequest(PumpSeriesUpdateRequest $request): bool
    {
        $updated = $this->update($request->seriesFields());
        if ($updated) {
            PumpSeriesAndType::updateForSeries($this, $request->types);
            PumpSeriesAndApplication::updateForSeries($this, $request->applications);
            Pump::clearCache();
        }
        return $updated;
    }
}
