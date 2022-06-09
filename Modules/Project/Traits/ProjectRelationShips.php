<?php

namespace Modules\Project\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

/**
 * Project Relationships
 */
trait ProjectRelationShips
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
        return $this->belongsTo(User::class, 'installer_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function designer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }
}
