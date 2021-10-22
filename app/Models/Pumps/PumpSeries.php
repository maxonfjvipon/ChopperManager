<?php

namespace App\Models\Pumps;

use App\Http\Requests\PumpSeriesStoreRequest;
use App\Http\Requests\PumpSeriesUpdateRequest;
use App\Models\Discount;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PumpSeries extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $softCascade = ['pump'];
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    public static function createFromRequest(PumpSeriesStoreRequest $request): self
    {
        $series = self::create($request->getSeriesFields());
        if ($series) {
            PumpSeriesAndType::createFromRequestForSeries($request, $series);
            PumpSeriesAndApplication::createFromRequestForSeries($request, $series);
        }
        return $series;
    }

    public function updateFromRequest(PumpSeriesUpdateRequest $request): bool
    {
        $updated = $this->update($request->getSeriesFields());
        if ($updated) {
            PumpSeriesAndType::updateFromRequestForSeries($request, $this);
            PumpSeriesAndApplication::updateFromRequestForSeries($request, $this);
        }
        return $updated;
    }

    public function getImplodedTypesAttribute(): string
    {
        return implode(", ", $this->types->map(fn($type) => $type->name)->toArray());
    }

    public function getImplodedApplicationsAttribute(): string
    {
        return implode(", ", $this->applications->map(fn($application) => $application->name)->toArray());
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PumpBrand::class, 'brand_id', 'id');
    }

    public function pump(): HasMany
    {
        return $this->hasMany(Pump::class, 'series_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PumpCategory::class, 'category_id');
    }

    public function power_adjustment(): BelongsTo
    {
        return $this->belongsTo(ElPowerAdjustment::class, 'power_adjustment_id');
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(PumpType::class, 'pump_series_and_types', 'series_id', 'type_id');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(PumpApplication::class, 'pump_series_and_applications', 'series_id', 'application_id');
    }

    public function discounts(): MorphMany
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
