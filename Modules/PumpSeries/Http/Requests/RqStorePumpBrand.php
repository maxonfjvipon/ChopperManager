<?php

namespace Modules\PumpSeries\Http\Requests;

use App\Models\Enums\Country;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class RqStorePumpBrand extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', "unique:pump_brands,name,NULL,NULL,deleted_at,NULL"],
            'country' => ['required', new EnumValue(Country::class)]
        ];
    }
}
