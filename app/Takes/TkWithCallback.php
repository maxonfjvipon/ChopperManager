<?php

namespace App\Takes;

use App\Takes\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkWithCallbackTest;

/**
 * Take that calls function.
 * @package App\Takes
 * @see TkWithCallbackTest
 */
final class TkWithCallback implements Take
{
    /**
     * @var callable $callback
     */
    private $callback;

    /**
     * @var Take $origin
     */
    private Take $origin;

    /**
     * Ctor.
     * @param callable $callback
     * @param Take $take
     */
    public function __construct(callable $callback, Take $take)
    {
        $this->callback = $callback;
        $this->origin = $take;
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        call_user_func($this->callback);
        return $this->origin->act($request);
    }
}