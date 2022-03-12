<?php

namespace App\Takes;

use App\Support\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Logical;
use Maxonfjvipon\Elegant_Elephant\Logical\LogicalOverloadable;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkTernaryTest;

/**
 * Ternary take.
 * @package App\Takes
 * @see TkTernaryTest
 */
final class TkTernary implements Take
{
    use LogicalOverloadable;

    /**
     * @var bool|Logical $condition
     */
    private bool|Logical $condition;

    /**
     * @var Take $origin
     */
    private Take $origin;

    /**
     * @var Take $alt
     */
    private Take $alt;

    /**
     * Ctor wrap.
     * @param bool|Logical $cond
     * @param Take $first
     * @param Take $alt
     */
    public function __construct(bool|Logical $cond, Take $first, Take $alt)
    {
        $this->condition = $cond;
        $this->origin = $first;
        $this->alt = $alt;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        if ($this->firstLogicalOverloaded($this->condition)) {
            return $this->origin->act($request);
        }
        return $this->alt->act($request);
    }
}
