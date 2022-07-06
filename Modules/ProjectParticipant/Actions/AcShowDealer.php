<?php

namespace Modules\ProjectParticipant\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Project\Entities\Project;
use Modules\Project\Support\ArrProjectsFilterData;
use Modules\ProjectParticipant\Entities\Dealer;
use Modules\ProjectParticipant\Transformers\RcDealerToShow;

/**
 * Show dealer action.
 */
final class AcShowDealer extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(Dealer $dealer)
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'dealer',
                    new RcDealerToShow($dealer->load([
                        'projects',
//                        => fn($query) => $query
//                            ->when(!\Auth::user()->isAdmin(), fn($query) => $query->where('created_by'))
//                            ->select('id', 'name', 'area_id', 'status'),
                        'projects.area' => fn($query) => $query->select('id', 'name'),
                        'projects.installer', 'projects.installer.area',
                        'projects.designer', 'projects.designer.area',
                        'projects.customer', 'projects.customer.area',
                        'projects.user' => fn($query) => $query->select('id', 'first_name', 'middle_name', 'last_name')
                    ]))
                ),
                new ArrProjectsFilterData(
                    new ArrMapped(
                        $dealer->projects->all(),
                        fn(Project $project) => $project->asArray()
                    )
                )
            )
        );
    }
}
