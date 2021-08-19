<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\HigherOrderCollectionProxy;

class PumpCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return HigherOrderCollectionProxy
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($pump) {
            return new PumpResource($pump);
        })->map->only('id',
            'part_num_main',
            'part_num_backup',
            'part_num_archive',
            'producer',
            'series',
            'name',
            'price',
            'currency',
            'weight',
            'power',
            'amperage',
            'connection_type',
            'min_liquid_temp',
            'max_liquid_temp',
            'between_axes_dist',
            'dn_input',
            'dn_output',
            'category',
            'regulation',
            'applications',
            'types'
        );
    }
}
