<?php

namespace App\Takes;

use App\Interfaces\Take;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Logical;
use Maxonfjvipon\Elegant_Elephant\Logical\LogicalOverloadable;
use Tests\Unit\Takes\TkTernaryTest;

/**
 * Ternary take.
 *
 * @see TkTernaryTest
 */
final class TkTernary extends TkEnvelope
{
    use LogicalOverloadable;

    /**
     * Ctor wrap.
     *
     * @throws Exception
     */
    public function __construct(private bool|Logical $cond, private Take $first, private Take $alt)
    {
        parent::__construct(
            $this->firstLogicalOverloaded($this->$this->cond)
                ? $this->first
                : $this->alt
        );
    }
}
