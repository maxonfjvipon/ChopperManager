<?php

namespace Modules\PumpProducer\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\Auth\Actions\RegisterUserAction;
use Modules\PumpProducer\Http\Requests\RegisterRequest;

class RegisterController extends \Modules\Auth\Http\Controllers\RegisterController
{
    /**
     * Register user
     *
     * @param RegisterRequest $request
     * @param RegisterUserAction $action
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request, RegisterUserAction $action): RedirectResponse
    {
        return parent::__register($request, $action);
    }
}
