<?php

namespace Modules\Pump\Http\Requests;

class PumpSeriesUpdateRequest extends PumpSeriesStoreRequest
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            ['is_discontinued' => ['required', 'boolean']],
        );
    }

    public function seriesFields(): array
    {
        return array_merge(
            parent::seriesFields(),
            ['is_discontinued' => $this->is_discontinued],
        );
    }
}
