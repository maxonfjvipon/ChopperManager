<?php

namespace Modules\User\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Currency;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\PumpSeries;
use Modules\Selection\Entities\SelectionType;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

trait UserRelationships
{
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * @return HasManyDeep
     */
    public function available_pumps(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->available_series(), (new PumpSeries())->pumps());
    }

    /**
     * Available brands for user
     *
     * @return HasManyDeep
     */
    public function available_brands(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->available_series(), (new PumpSeries())->brand());
    }

    /**
     * Available series for pump-manager users
     * @return Collection|array|BelongsToMany
     */
    public function available_series(): Collection|array|BelongsToMany
    {
        return $this->belongsToMany(PumpSeries::class, 'users_and_pump_series', 'user_id', 'series_id');
    }

    /**
     * Available selection types for user
     *
     * @return BelongsToMany
     */
    public function available_selection_types(): BelongsToMany
    {
        return $this->belongsToMany(SelectionType::class, 'users_and_selection_types', 'user_id', 'type_id');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
