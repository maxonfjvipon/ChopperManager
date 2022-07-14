<?php

namespace App\Takes;

use App\Interfaces\Take;
use Closure;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ctor.
 */
final class TkFromCallback implements Take
{
    /**
     * Ctor.
     */
    public function __construct(private Closure $callback)
    {
    }

    /**
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        $take = call_user_func($this->callback);
        if (! $take instanceof Take) {
            throw new Exception('Callback must return an instance of Take');
        }

        return $take->act($request);
    }
}
