<?php

namespace Modules\AdminPanel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends StoreTenantRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'unique:tenants,name,' . $this->id],
            'domain' => ['required', 'unique:tenants,domain,' . $this->id],
            'database' => ['required', 'unique:tenants,database,' . $this->id],
            'type_id' => ['required', 'exists:tenant_types,id'],
            'is_active' => ['required', 'boolean'],
            'has_registration' => ['required', 'boolean'],
            'selection_type_ids' => ['sometimes', 'required', 'array'],
        ];
    }
}
