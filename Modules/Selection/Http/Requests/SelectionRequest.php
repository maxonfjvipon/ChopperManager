<?php

namespace Modules\Selection\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\Pure;

abstract class SelectionRequest extends FormRequest
{
    private string $separator = ",";

    protected function prepareForValidation()
    {
        $this->merge([
            'pump_series_ids' => $this->imploded(array_filter($this->pump_series_ids, fn($item) => gettype($item) === 'integer')),
            'pump_brand_ids' => $this->imploded($this->pump_brand_ids),
            'custom_range' => $this->imploded($this->custom_range),

            'power_adjustment_ids' => $this->imploded($this->power_adjustment_ids),
            'pump_type_ids' => $this->imploded($this->pump_type_ids),
            'connection_type_ids' => $this->imploded($this->connection_type_ids),
            'mains_connection_ids' => $this->imploded($this->mains_connection_ids),
        ]);
    }

    #[Pure] protected function imploded($array): ?string
    {
        return $array ? implode($this->separator, $array) : null;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
