<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Logical;
use Maxonfjvipon\Elegant_Elephant\Numerable;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumSticky;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Http\Requests\RqMakeSelection;
use Modules\Selection\Support\Performance\PpQStart;
use Modules\Selection\Support\Point\IntersectionPoint;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;

/**
 * Is pump appropriate for selection
 */
final class PumpIsGoodToSelect implements Logical
{

    /**
     * Ctor.
     * @param RqMakeSelection $request
     * @param Pump $pump
     * @param int $mainPumpsCount
     * @param Numerable $qEnd
     */
    public function __construct(
        private RqMakeSelection $request,
        private Pump            $pump,
        private int             $mainPumpsCount,
        private Numerable       $qEnd,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function asBool(): bool
    {
        $qStart = new NumSticky(new PpQStart($this->pump->performance(), $this->mainPumpsCount));
        $intersectionPoint = new IntersectionPoint(
            new EqFromPumpCoefficients(
                $this->pump->coefficientsAt($this->mainPumpsCount)
            ),
            $this->request->flow,
            $this->request->head
        );
        return $this->request->flow >= $qStart->asNumber()
            && $intersectionPoint->x() >= $qStart->asNumber()
            && $intersectionPoint->x() <= $this->qEnd->asNumber()
            && $intersectionPoint->y() >= $this->request->head + $this->request->head * (($this->request->deviation ?? 0) / 100);
    }
}
