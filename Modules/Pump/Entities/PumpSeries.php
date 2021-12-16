<?php

namespace Modules\Pump\Entities;

use Modules\Pump\Http\Requests\PumpSeriesStoreRequest;
use Modules\Pump\Http\Requests\PumpSeriesUpdateRequest;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pump\Traits\Discountable;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PumpSeries extends Model
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, UsesTenantConnection, Discountable;

    protected $guarded = [];
    public $timestamps = false;
    protected $softCascade = ['single_pumps', 'double_pumps'];

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

    private function implodedAttributes($attributes): string
    {
        return implode(", ", $attributes->map(fn($attribute) => $attribute->name)->toArray());
    }

    public function getImplodedTypesAttribute(): string
    {
        return $this->implodedAttributes($this->types);
    }

    public function getImplodedApplicationsAttribute(): string
    {
        return $this->implodedAttributes($this->applications);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PumpBrand::class, 'brand_id', 'id');
    }

    public function pumps(): HasMany
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
}
