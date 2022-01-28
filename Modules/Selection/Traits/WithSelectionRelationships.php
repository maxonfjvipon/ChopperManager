<?php

namespace Modules\Selection\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Entities\Project;
use Modules\Pump\Entities\DoublePumpWorkScheme;
use Modules\Pump\Entities\Pump;

/**
 * Selection relationships
 * @package Modules\Selection\Traits
 */
trait WithSelectionRelationships
{
    // RELATIONSHIPS
    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class, 'pump_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function dp_work_scheme(): BelongsTo
    {
        return $this->belongsTo(DoublePumpWorkScheme::class, 'dp_work_scheme_id');
    }
}
