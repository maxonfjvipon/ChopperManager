<?php

namespace Modules\Selection\Transformers\SelectionResources;

/**
 * Able to transform string to ids array.
 */
trait HasIdsArrayFromString
{
    public function idsArrayFromString(?string $string): array
    {
        return (bool) $string
            ? array_map('intval', explode(',', $string))
            : [];
    }
}
