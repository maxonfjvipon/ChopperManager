<?php

namespace Modules\ProjectParticipant\Actions;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrObject;
use Modules\Project\Support\ArrProjectsFilterData;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Transformers\RcContractorToShow;

/**
 * Show contractor action.
 */
final class AcShowContractor extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(Contractor $contractor)
    {
        parent::__construct(
            new ArrMerged(
                new ArrObject(
                    'contractor',
                    new RcContractorToShow(
                        $contractor->withAllProjects()
                    )
                ),
                new ArrProjectsFilterData(
                    new ArrayableOf(
                        $contractor->projects->all()
                    )
                )
            )
        );
    }
}
