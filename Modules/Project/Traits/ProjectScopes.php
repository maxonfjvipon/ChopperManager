<?php

namespace Modules\Project\Traits;

/**
 * Project scopes.
 */
trait ProjectScopes
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithAllContractors($query): mixed
    {
        return $query->with(array_merge(
                [
                    'installer' => ($callback = fn($query) => $query->select('id', 'name')),
                    'designer' => $callback,
                    'customer' => $callback,
                ],
                \Auth::user()->isAdmin()
                    ? ['dealer' => fn($query) => $query->select('id', 'first_name', 'middle_name', 'last_name')]
                    : []
            )
        );
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithPumpStations($query): mixed
    {
        return $query->with([
            'selections.pump_stations' => fn($query) => $query->select('id', 'selection_id', 'full_name')
        ]);
    }
}
