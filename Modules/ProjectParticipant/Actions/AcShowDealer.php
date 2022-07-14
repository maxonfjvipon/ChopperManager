<?php

namespace Modules\ProjectParticipant\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
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
                    new RcDealerToShow(
                        $dealer->load([
                            'projects',
                            'projects.area' => $areaCallback = fn ($query) => $query->select('id', 'name'),
                            'projects.installer',
                            'projects.installer.area' => $areaCallback,
                            'projects.designer',
                            'projects.designer.area' => $areaCallback,
                            'projects.customer',
                            'projects.customer.area' => $areaCallback,
                            'projects.user' => fn ($query) => $query->select('id', 'first_name', 'middle_name', 'last_name'),
                        ])
                    )
                ),
                new ArrProjectsFilterData(
                    new ArrayableOf(
                        $dealer->projects->all()
                    )
                )
            )
        );
    }
}
