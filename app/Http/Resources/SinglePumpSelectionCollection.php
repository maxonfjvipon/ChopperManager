<?php

namespace App\Http\Resources;

use App\Models\Selections\Single\SinglePumpSelection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\HigherOrderCollectionProxy;

class SinglePumpSelectionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return HigherOrderCollectionProxy
     */
    public function toArray($request)
    {
        return $this->collection->map->only('pumps_count', 'id', 'pump_id');
    }
}
