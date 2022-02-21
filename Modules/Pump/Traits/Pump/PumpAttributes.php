<?php

namespace Modules\Pump\Traits\Pump;

/**
 * Pump attributes
 */
trait PumpAttributes
{
    public function getIsDiscontinuedWithSeriesAttribute(): bool
    {
        return $this->series->is_discontinued
            ? true
            : $this->getOriginal('is_discontinued');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->brand->name} {$this->name}";
    }

    public function getTypesAttribute(): string
    {
        return $this->series->imploded_types;
    }

    public function getApplicationsAttribute(): string
    {
        return $this->series->imploded_applications;
    }
}
