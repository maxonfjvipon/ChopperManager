<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RqUploadMedia extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array'],
            'folder' => ['required', 'string'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
