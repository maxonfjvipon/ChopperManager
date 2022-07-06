<?php

namespace Modules\Project\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Selection\Entities\Selection;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\User\Entities\User;

/**
 * Project Relationships
 */
trait ProjectRelationships
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function installer(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'installer_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function designer(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'designer_id');
    }

    /**
     * @return BelongsTo
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    /**
     * @return HasMany
     */
    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }
}
