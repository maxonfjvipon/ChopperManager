<?php

namespace Modules\Selection\Actions;

use Illuminate\Http\Request;
use Modules\Selection\Entities\Selection;

/**
 * Destroy selection action.
 */
final class AcDestroySelection
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
        $selection = Selection::find($this->request->selection);
        $selection->project->upd();
        $selection->delete();
    }
}
