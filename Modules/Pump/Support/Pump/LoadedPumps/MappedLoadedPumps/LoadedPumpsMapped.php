<?php

namespace Modules\Pump\Support\Pump\LoadedPumps\MappedLoadedPumps;

use Exception;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrValues;
use Modules\Pump\Entities\Pump;

/**
 * Mapped loaded pumps
 */
final class LoadedPumpsMapped implements Arrayable
{
    /**
     * @var Arrayable $pumps
     */
    private Arrayable $pumps;

    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor.
     * @param Arrayable $pumps
     * @param Request $request
     */
    public function __construct(Arrayable $pumps, Request $request)
    {
        $this->pumps = $pumps;
        $this->request = $request;
    }

    public function asArray(): array
    {
        return (new ArrValues(
            match ($this->request->pumpable_type) {
                Pump::$DOUBLE_PUMP => new DoubleLoadedPumpMapped($this->pumps),
                Pump::$SINGLE_PUMP => new SingleLoadedPumpsMapped($this->pumps)
            })
        )->asArray();
    }
}