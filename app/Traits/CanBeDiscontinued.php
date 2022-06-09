<?php

namespace App\Traits;

/**
 * Can be discontinued trait.
 */
trait CanBeDiscontinued
{
    public function scopeNotDiscontinued($query)
    {
        return $query->where('is_discontinued', false);
    }
}
