<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RqUpdateDiscount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discountable_id' => ['required', 'exists:discounts,discountable_id'],
            'discountable_type' => ['required', 'exists:discounts,discountable_type'],
            'user_id' => ['required', 'exists:users,id'],
            'value' => ['required', 'nullable', 'min:0', 'max:100'],
        ];
    }
}
