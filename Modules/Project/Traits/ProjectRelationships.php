<?php

namespace Modules\Project\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\User;

/**
 * Project Relationships.
 */
trait ProjectRelationships
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function installer(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'installer_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'customer_id');
    }

    public function designer(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'designer_id');
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }
}
