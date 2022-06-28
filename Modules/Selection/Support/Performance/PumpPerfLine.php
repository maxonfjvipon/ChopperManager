<?php

namespace Modules\Selection\Support\Performance;

use Maxonfjvipon\Elegant_Elephant\Any\FirstOf;
use Maxonfjvipon\Elegant_Elephant\Any\LastOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrFromCallback;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrRange;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Maxonfjvipon\Elegant_Elephant\Logical\EqualityOf;
use Maxonfjvipon\Elegant_Elephant\Logical\Negation;
use Maxonfjvipon\Elegant_Elephant\Numerable\LengthOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumerableOf;
use Maxonfjvipon\Elegant_Elephant\Numerable\NumSticky;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\Point\SimplePoint;
use Modules\Selection\Support\Regression\EqFromPumpCoefficients;

/**
 * Pump performance line
 */
final class PumpPerfLine extends ArrEnvelope
{
    /**
     * Ctor.
     * @param Pump $pump
     * @param int $position
     */
    public function __construct(private Pump $pump, private int $position = 1)
    {
        parent::__construct(
            new ArrFromCallback(
                function () {
                    $eq = new EqFromPumpCoefficients(
                        $this->pump->coefficientsAt($this->position)
                    );
                    return new ArrMapped(
                        new ArrMerged(
                            $lines = new ArrSticky(
                                new ArrMapped(
                                    new ArrRange(
                                        $xxFirst = new NumSticky(
                                            new NumerableOf(
                                                new FirstOf(
                                                    $xx = new ArrSticky(
                                                        new PerformanceQs(
                                                            $this->pump->performance(),
                                                            $this->position,
                                                        )
                                                    )
                                                )
                                            ),
                                        ),
                                        $xxLast = new NumSticky(
                                            new NumerableOf(
                                                new LastOf($xx)
                                            )
                                        ),
                                        $qStep = new NumSticky(new FlowStep($xx)),
                                    ),
                                    fn($x) => new SimplePoint($x, $eq->y($x))
                                )
                            ),
                            new ArrIf(
                                new Negation(
                                    new EqualityOf(
                                        new LengthOf($lines),
                                        (($xxLastAsNum = $xxLast->asNumber()) - $xxFirst->asNumber()) / $qStep->asNumber()
                                    )
                                ),
                                fn() => [new SimplePoint($xxLastAsNum, $eq->y($xxLastAsNum))]
                            )
                        ),
                        fn(SimplePoint $point) => $point->asArray()
                    );
                }
            )
        );
    }
}
