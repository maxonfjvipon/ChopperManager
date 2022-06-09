<?php

namespace Modules\Project\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Project\Entities\ProjectStatus;

class RqStoreProject extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
            'status' => ['required', new EnumValue(ProjectStatus::class)],
            'description' => ['sometimes', 'nullable', 'string'],
            'installer_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'customer_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'designer_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'dealer_id' => ['sometimes', 'nullable', 'exists:users,id'],
        ];
    }

    /**
     * @return array
     */
    public function projectProps(): array
    {
        return [
            'name' => $this->name,
            'area_id' => $this->area_id,
            'status' => $this->status,
            'description' => $this->description,
            'installer_id' => $this->installer_id,
            'customer_id' => $this->customer_id,
            'designer_id' => $this->designer_id,
            'dealer_id' => $this->dealer_id
        ];
    }
}
