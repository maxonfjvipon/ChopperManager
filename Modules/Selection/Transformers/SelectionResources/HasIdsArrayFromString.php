<?php

namespace Modules\Selection\Transformers\SelectionResources;

/**
 * Able to transform string to ids array
 */
trait HasIdsArrayFromString
{
    /**
     * @param string|null $string
     * @return array
     */
    public function idsArrayFromString(?string $string): array
    {
        return !!$string
            ? array_map('intval', explode(",", $string))
            : [];
    }
}
