<?php

namespace Modules\Project\Support;

use App\Support\TxtView;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Modules\Project\Entities\Project;

/**
 * Export project view as {@Text}.
 */
final class TxtExportProjectView implements Text
{
    /**
     * Ctor.
     */
    public function __construct(private Project $project, private Request $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asString(): string
    {
        return (new TxtView('project::project_export', [
            'project' => $this->project->readyForExport($this->request->selection_ids),
            'request' => $this->request,
        ]))->asString();
    }
}
