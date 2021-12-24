<?php

namespace Modules\Pump\Http\Requests;

use App\Rules\ArrayExistsInArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddPumpToProjectsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $projectIds = Auth::user()->projects()->pluck('id')->all();
        return [
            'project_ids' => ['required', 'array', new ArrayExistsInArray($projectIds)],
            'pumps_count' => ['sometimes', 'required', 'numeric', 'min:1', 'max:5'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
