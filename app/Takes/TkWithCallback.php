<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Take that calls function.
 * @package App\Takes
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
     * Ctor wrap.
     * @param callable $callable
     * @param Take $take
     * @return TkWithCallback
     */
    public static function new(callable $callable, Take $take): TkWithCallback
    {
        return new self($callable, $take);
    }

    /**
     * Ctor.
     * @param callable $callback
     * @param Take $take
     */
    private function __construct(callable $callback, Take $take)
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
