<?php

namespace Modules\PumpSeries\Http\Requests;

use App\Models\Enums\Currency;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property int    $brand_id
 */
class RqStorePumpSeries extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'exists:pump_brands,id'],
            'name' => ['required'],
            'currency' => ['required', new EnumValue(Currency::class)],
        ];
    }
}
