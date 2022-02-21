<?php

namespace Modules\Pump\Traits\PumpSeries;

use Illuminate\Http\Request;
use Modules\AdminPanel\Entities\TenantType;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpCategory;

/**
 * Pump series scopes.
 */
trait PumpSeriesScopes
{
    // SCOPES
    public function scopeNotDiscontinued($query)
    {
        return $query->whereIsDiscontinued(false);
    }

    public function scopeAvailable($query)
    {
        if ($this->getTenantModel()::checkCurrent()) {
            return match ($this->getTenantModel()::current()->type->id) {
                TenantType::$PUMPMANAGER => $query
            };
        }
        return $query;
    }

    public function scopeCategorized($query, Request $request)
    {
        return match ($request->pumpable_type) {
            Pump::$DOUBLE_PUMP => $query->double(),
            default => $query->single()
        };
    }

    protected function scopeOnCategory($query, $categoryId)
    {
        return $query->whereCategoryId($categoryId);
    }

    protected function scopeDouble($query)
    {
        return $query->onCategory(PumpCategory::$DOUBLE_PUMP);
    }

    protected function scopeSingle($query)
    {
        return $query->onCategory(PumpCategory::$SINGLE_PUMP);
    }
}
