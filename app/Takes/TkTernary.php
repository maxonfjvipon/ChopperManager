<?php

namespace App\Takes;

use App\Interfaces\Take;
use Exception;
use Maxonfjvipon\Elegant_Elephant\Logical;
use Maxonfjvipon\Elegant_Elephant\Logical\LogicalOverloadable;
use Tests\Unit\Takes\TkTernaryTest;

/**
 * Ternary take.
 * @package App\Takes
 * @see TkTernaryTest
 */
final class TkTernary extends TkEnvelope
{
    use LogicalOverloadable;

    /**
     * Ctor wrap.
     * @param bool|Logical $cond
     * @param Take $first
     * @param Take $alt
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
