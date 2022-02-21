<?php

namespace Modules\Selection\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Pump\Entities\Pump;

/**
 * Selection create component as {@Text}
 * @package Modules\Selection\Support
 */
final class TxtSelectionsCreateComponent implements Text
{
    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor wrap.
     * @param Request|null $request
     * @return TxtSelectionsCreateComponent
     */
    public static function new(Request $request = null): TxtSelectionsCreateComponent
    {
        return new self($request);
    }

    /**
     * Ctor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return match ($this->request->pumpable_type) {
            Pump::$DOUBLE_PUMP => "Selection::DoublePump",
            default => "Selection::SinglePump"
        };
    }
}
