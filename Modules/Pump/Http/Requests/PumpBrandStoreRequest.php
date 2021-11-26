<?php

namespace Modules\Pump\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class PumpBrandStoreRequest extends FormRequest
{
    use UsesTenantModel;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', "unique:tenant.pump_brands,name,NULL,NULL,deleted_at,NULL"]
        ];
    }
}
