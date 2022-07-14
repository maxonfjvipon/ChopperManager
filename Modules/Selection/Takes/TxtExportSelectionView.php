<?php

namespace Modules\Selection\Takes;

use App\Support\TxtView;
use Exception;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Selection\Entities\Selection;

/**
 * Export selection view as {@Text}.
 */
final class TxtExportSelectionView implements Text
{
    private Selection $selection;

    private Request $request;

    /**
     * Ctor.
     */
    public function __construct(Selection $selection, Request $request)
    {
        $this->selection = $selection;
        $this->request = $request;
    }

    /**
     * @throws Exception
     */
    public function asString(): string
    {
        return (new TxtView('selection::selection_to_export', [
            'selection' => $this->selection->readyForExport(),
            'request' => $this->request,
        ]))->asString();
    }
}
