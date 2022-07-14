<?php

namespace Modules\Project\Actions;

use App\Interfaces\InvokableAction;
use Modules\Project\Entities\Project;
use Modules\Project\Http\Requests\RqUpdateProject;

/**
 * Update project action.
 */
final class AcUpdateProject implements InvokableAction
{
    /**
     * Ctor.
     */
    public function __construct(private RqUpdateProject $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(): void
    {
        Project::find($this->request->project)->update($this->request->projectProps());
    }
}
