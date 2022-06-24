<?php

namespace Modules\PumpSeries\Transformers;

use App\Models\Enums\Currency;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;
use Modules\PumpSeries\Entities\PumpBrand;

final class RcPumpSeriesProps extends JsonResource
{
    /**
     * Ctor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     * @throws Exception
     */
    public function toArray($request): array
    {
        return [
            'brands' => PumpBrand::allForFiltering(),
            'currencies' => Currency::asArrayForSelect()
        ];
    }
}
