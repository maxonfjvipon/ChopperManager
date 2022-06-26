<?php

namespace Modules\User\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Project;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\User\Entities\Contractor;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * User relationships.
 */
trait UserRelationships
{
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
     * @return BelongsToMany
     */
    public function available_series(): BelongsToMany
    {
        return $this->belongsToMany(PumpSeries::class, 'users_pump_series', 'user_id', 'series_id');
    }

    /**
     * @return BelongsToMany
     */
    public function contractors(): BelongsToMany
    {
        return $this->belongsToMany(Contractor::class, 'users_contractors', 'user_id', 'contractor_id');
    }

//    public function discounts(): HasMany
//    {
//        return $this->hasMany(Discount::class, 'user_id');
//    }

    /**
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

//    public function country(): BelongsTo
//    {
//        return $this->belongsTo(Country::class);
//    }
//
//    public function currency(): BelongsTo
//    {
//        return $this->belongsTo(Currency::class);
//    }
}
