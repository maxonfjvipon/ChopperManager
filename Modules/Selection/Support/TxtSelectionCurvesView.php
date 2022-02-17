<?php

namespace Modules\Selection\Support;

use App\Support\TxtView;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Selection\Entities\Selection;

/**
 * Selection curves view as {@Text}
 */
final class TxtSelectionCurvesView implements Text
{
    /**
     * @var Selection $selection
     */
    private Selection $selection;

    /**
     * Ctor wrap.
     * @param Selection $selection
     * @return TxtSelectionCurvesView
     */
    public static function new(Selection $selection): TxtSelectionCurvesView
    {
        return new self($selection);
    }

    /**
     * Ctor.
     * @param Selection $selection
     */
    public function __construct(Selection $selection)
    {
        $this->selection = $selection;
    }

    /**
     * @inheritDoc
     */
    public function asString(): string
    {
        return TxtView::new(
            'selection::selection_perf_curves',
            $this->selection
                ->withCurves()
                ->curves_data
        )->asString();
    }
}
