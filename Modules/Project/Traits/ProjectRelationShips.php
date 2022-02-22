<?php

namespace Modules\Project\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Project\Entities\ProjectDeliveryStatus;
use Modules\Project\Entities\ProjectStatus;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\User;

/**
 * Project Relationships
 */
trait ProjectRelationShips
{
    public function all_selections()
    {
        return $this->hasMany(Selection::class)->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function status(): HasOne
    {
        return $this->hasOne(ProjectStatus::class, 'id', 'status_id');
    }

    /**
     * @return HasOne
     */
    public function delivery_status(): HasOne
    {
        return $this->hasOne(ProjectDeliveryStatus::class, 'id', 'delivery_status_id');
    }
}
