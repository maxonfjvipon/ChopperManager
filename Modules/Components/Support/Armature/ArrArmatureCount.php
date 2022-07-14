<?php

namespace Modules\Components\Support\Armature;

use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Modules\Components\Entities\Armature;

/**
 * Armature count array
 * Return armature with count in following structure
 * ['armature' => $armature, 'count' => $count].
 */
final class ArrArmatureCount implements Arrayable
{
    /**
     * Ctor.
     *
     * @param Armature|null $armature
     * @param int           $count
     */
    public function __construct(private ?Armature $armature, private int $count)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function asArray(): array
    {
        return [
            'armature' => $this->armature,
            'count' => $this->count,
        ];
    }
}
