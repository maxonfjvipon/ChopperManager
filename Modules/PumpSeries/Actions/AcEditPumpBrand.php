<?php

namespace Modules\PumpSeries\Actions;

use App\Models\Enums\Country;
use Exception;
use Modules\PumpSeries\Entities\PumpBrand;
use Modules\PumpSeries\Transformers\RcPumpBrand;

final class AcEditPumpBrand
{
    /**
     * @param PumpBrand $brand
     * @return array
     * @throws Exception
     */
    public function __invoke(PumpBrand $brand): array
    {
        return [
            'brand' => new RcPumpBrand($brand),
            'countries' => Country::asArrayForSelect()
        ];
    }
}
