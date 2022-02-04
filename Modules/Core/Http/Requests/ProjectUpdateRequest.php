<?php

namespace Modules\Core\Http\Requests;

class ProjectUpdateRequest extends ProjectStoreRequest
{
    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'status_id' => ['sometimes', 'exists:tenant.project_statuses,id'],
            'delivery_status_id' => ['sometimes', 'exists:tenant.project_delivery_statuses,id'],
            'comment' => ['sometimes', 'nullable', 'string']
        ];
    }
}
