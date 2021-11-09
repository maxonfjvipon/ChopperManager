<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param string $guard
     * @param string $loginBy
     * @return void
     *
     * @throws ValidationException
     */
    public function authenticate($guard = 'web', $loginBy = 'email')
    {
        $this->ensureIsNotRateLimited($loginBy);

        if (! Auth::guard($guard)->attempt($this->only($loginBy, 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($loginBy));

            throw ValidationException::withMessages([
                $loginBy => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($loginBy));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param $loginBy
     * @return void
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited($loginBy)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($loginBy), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey($loginBy));

        throw ValidationException::withMessages([
            $loginBy => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @param $loginBy
     * @return string
     */
    public function throttleKey($loginBy): string
    {
        return Str::lower($this->input($loginBy)).'|'.$this->ip();
    }
}
