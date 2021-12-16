<?php


namespace Modules\PumpProducer\Http\Requests;


use Modules\User\Http\Requests\CreateUserRequest;

class PPCreateUserRequest extends CreateUserRequest
{
    public function rules(): array
    {
    }

    public function availableProps(): array
    {
        // TODO: Implement availableProps() method.
    }

    public function userProps(): array
    {
        // TODO: Implement userProps() method.
    }
}
