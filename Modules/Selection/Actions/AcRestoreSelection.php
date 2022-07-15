<?php

namespace Modules\Selection\Actions;

use Illuminate\Http\Request;
use Modules\Selection\Entities\Selection;

/**
 * Restore request action.
 */
final class AcRestoreSelection
{
    /**
     * Ctor.
     *
     * @param  Request  $request
     */
    public function __construct(private Request $request)
    {
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        $selection = Selection::withTrashed()->find($this->request->selection);
        $selection->restore();
        $selection->project->upd();
    }
}
