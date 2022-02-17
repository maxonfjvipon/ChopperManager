<?php

namespace Modules\Selection\Transformers\SelectionResources;

use App\Support\TenantStorage;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrExploded;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

abstract class PumpSelectionResource extends JsonResource
{
    /**
     * @throws Exception
     */
    protected function arrayOfIntsFromString($string): array
    {
        return $string === null
            ? []
            : ArrMapped::new(
                ArrExploded::byComma($string),
                'intval'
            )->asArray();
    }
}
