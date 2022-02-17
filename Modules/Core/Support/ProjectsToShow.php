<?php

namespace Modules\Core\Support;

use Exception;
use JetBrains\PhpStorm\Pure;
use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 * Projects to show
 */
final class ProjectsToShow implements Arrayable
{
    /**
     * @return ProjectsToShow
     */
    public static function new(): ProjectsToShow
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function asArray(): array
    {
        return [
            'projects' => auth()->user()
                ->projects()
                ->withCount('selections')
                ->with(['selections' => function ($query) {
                    $query->select('id', 'project_id', 'selected_pump_name', 'flow', 'head');
                }])->get()->all()
        ];
    }
}
