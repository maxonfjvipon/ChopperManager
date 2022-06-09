<?php

namespace Modules\PumpSeries\Http\Requests;

final class RqUpdatePumpSeries extends RqStorePumpSeries
{
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            ['is_discontinued' => ['required', 'boolean']],
        );
    }
}
