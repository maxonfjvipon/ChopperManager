<?php

namespace Modules\AdminPanel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function tenantProps(): array
    {
        return [
            'name' => $this->name,
            'domain' => $this->domain,
            'database' => $this->database,
            'is_active' => $this->is_active,
            'has_registration' => $this->has_registration,
            'type_id' => $this->type_id,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'unique:tenants,name'],
            'domain' => ['required', 'unique:tenants,domain'],
            'database' => ['required', 'unique:tenants,database'],
            'is_active' => ['required', 'boolean'],
            'has_registration' => ['required', 'boolean'],
            'selection_type_ids' => ['required', 'array'],
            'type_id' => ['required', 'exists:tenant_types,id'],
        ];
    }
}
