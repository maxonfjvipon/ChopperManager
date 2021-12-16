<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Contracts\ChangeUser\ChangeUserContract;

abstract class UpdateUserRequest extends FormRequest implements ChangeUserContract
{
}
