<?php

namespace Modules\User\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Http\Requests\Interfaces\UserCreatable;

class CreateUserRequest extends FormRequest implements UserCreatable
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        throw new Exception("This shouldn't have happened");
    }

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
     * @return array
     * @throws Exception
     */
    public function userProps(): array
    {
        throw new Exception("This shouldn't have happened");
    }

    /**
     * @return array
     * @throws Exception
     */
    public function availableProps(): array
    {
        throw new Exception("This shouldn't have happened");
    }
}
