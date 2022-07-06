<?php

namespace Modules\User\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\Entities\Project;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;
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

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
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
