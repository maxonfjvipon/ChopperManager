<?php

namespace Modules\Selection\Actions;

use DB;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Entities\Project;
use Modules\Selection\Entities\Selection;
use Modules\Selection\Http\Requests\RqStoreSelection;

/**
 * Store selection action.
 */
final class AcStoreSelection
{
    /**
     * Ctor.
     *
     * @param RqStoreSelection $request
     * @param Project          $project
     */
    public function __construct(
        private RqStoreSelection $request,
        private Project $project
    ) {
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        $this->project->updateTimestamps();
        $selection = Selection::create(
            array_merge(
                $this->request->selectionProps(),
                [
                    'project_id' => $this->project->id,
                    'created_by' => Auth::id(),
                ]
            )
        );
        DB::table('pump_stations')->insert(array_map(
            fn (array $station) => array_merge(
                $station,
                [
                    'selection_id' => $selection->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ),
            $this->request->added_stations
        ));
    }
}
