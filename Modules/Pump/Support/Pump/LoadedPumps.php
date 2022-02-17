<?php

namespace Modules\Pump\Support\Pump;

use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;

/**
 * Loaded pumps
 */
final class LoadedPumps implements Arrayable
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor.
     * @param Request $req
     * @return LoadedPumps
     */
    public static function new(Request $req)
    {
        return new self($req);
    }

    /**
     * Ctor.
     * @param Request $req
     */
    public function __construct(Request $req)
    {
        $this->request = $req;
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {

        return ArrMapped::new(

        )->asArray();
    }
}
