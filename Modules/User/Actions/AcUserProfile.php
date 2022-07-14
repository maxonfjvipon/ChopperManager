<?php

namespace Modules\User\Actions;

use Exception;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\User\Entities\Area;
use Modules\User\Transformers\RcUserProfile;

/**
 * User profile action.
 */
final class AcUserProfile implements Arrayable
{
    /**
     * @throws Exception
     */
    public function asArray(): array
    {
        return [
            'user' => new RcUserProfile(\Auth::user()),
            'filter_data' => [
                'areas' => Area::allOrCached(),
            ],
        ];
    }
}
