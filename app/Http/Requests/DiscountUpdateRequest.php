<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'discountable_id' => ['required', 'exists:discounts,discountable_id'],
            'discountable_type' => ['required', 'exists:discounts,discountable_type'],
            'user_id' => ['required', 'exists:users,id'],
            'value' => ['required', 'nullable', 'min:0', 'max:100']
        ];
    }
}
