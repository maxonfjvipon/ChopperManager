<?php

namespace Modules\Project\Traits;

use Auth;

/**
 * Project scopes.
 */
trait ProjectScopes
{
    /**
     * @param $query
     */
    public function scopeWithAllParticipants($query): mixed
    {
        return $query->with(array_merge(
                [
                    'installer' => ($callback = fn ($query) => $query->select('id', 'name', 'area_id', 'itn')),
                    'designer' => $callback,
                    'customer' => $callback,
                ],
                Auth::user()->isAdmin() ? [
                    'dealer' => fn ($query) => $query->select('id', 'name'),
                    'user' => fn ($query) => $query->select('id', 'first_name', 'middle_name', 'last_name'),
                ] : []
            )
        );
    }

    /**
     * @param $query
     */
    public function scopeWithPumpStations($query): mixed
    {
        return $query->with([
            'selections.pump_stations' => fn ($query) => $query->select('id', 'selection_id', 'full_name'),
        ]);
    }
}
