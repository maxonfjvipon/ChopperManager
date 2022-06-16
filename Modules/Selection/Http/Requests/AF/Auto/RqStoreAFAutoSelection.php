<?php

namespace Modules\Selection\Http\Requests\AF\Auto;

use Exception;
use Modules\Selection\Http\Requests\WS\Auto\RqStoreWSAutoSelection;
use Modules\Selection\Traits\AFSelectionRequestHelpers;

/**
 * @property int $gate_valves_count
 * @property boolean $avr
 * @property boolean $kkv
 * @property boolean $on_street
 * @property float $jockey_flow
 * @property float $jockey_head
 * @property string $jockey_series_ids
 * @property string $jockey_brand_ids
 */
final class RqStoreAFAutoSelection extends RqStoreWSAutoSelection
{
    use AFSelectionRequestHelpers;

    /**
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            $this->afSelectionRules(),
        );
    }


    public function selectionProps(): array
    {
        return array_merge(
            parent::selectionProps(),
            $this->afSelectionProps()
        );
    }
}
