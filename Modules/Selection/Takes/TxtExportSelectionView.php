<?php

namespace Modules\Selection\Takes;

use App\Support\TxtView;
use Exception;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Selection\Entities\Selection;

/**
 * Export selection view as {@Text}
 * @package Modules\Selection\Takes
 */
final class TxtExportSelectionView implements Text
{
    /**
     * @var Selection $selection
     */
    private Selection $selection;

    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * Ctor wrap.
     * @param Selection $selection
     * @param Request $request
     * @return TxtExportSelectionView
     */
    public static function new(Selection $selection, Request $request): TxtExportSelectionView
    {
        return new self($selection, $request);
    }

    /**
     * Ctor.
     * @param Selection $selection
     * @param Request $request
     */
    public function __construct(Selection $selection, Request $request)
    {
        $this->selection = $selection;
        $this->request = $request;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function asString(): string
    {
        return TxtView::new('selection::selection_to_export', [
            'selection' => $this->selection->readyForExport(),
            'request' => $this->request
        ])->asString();
    }
}
