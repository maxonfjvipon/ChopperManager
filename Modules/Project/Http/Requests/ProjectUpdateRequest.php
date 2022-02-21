<?php

namespace Modules\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ProjectUpdateRequest extends FormRequest
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

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'status_id' => ['sometimes', 'exists:project_statuses,id'],
            'delivery_status_id' => ['sometimes', 'exists:project_delivery_statuses,id'],
            'comment' => ['sometimes', 'nullable', 'string']
        ];
    }
}
