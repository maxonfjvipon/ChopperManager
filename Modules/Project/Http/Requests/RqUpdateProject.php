<?php

namespace Modules\Project\Http\Requests;

use Modules\Project\Rules\ProjectContractorRegex;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\User\Entities\Area;

/**
 * @property string $project
 */
final class RqUpdateProject extends RqStoreProject
{
    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        foreach ([
            'installer' => $this->installer,
            'designer' => $this->designer,
            'customer' => $this->customer,
        ] as $name => $contractor) {
            if ((bool) $contractor && !preg_match(ProjectContractorRegex::REGEX, $contractor)) {
                $parts = explode(' / ', $contractor);
                $this->merge([
                    $name => implode(
                        Contractor::SEPARATOR,
                        [$parts[0], $parts[1], Area::firstWhere('name', $parts[2])->fullRegionKladrId()]
                    ),
                ]);
            }
        }
    }
}
