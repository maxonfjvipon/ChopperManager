<?php

namespace App\Takes;

use App\Support\Take;
use Maxonfjvipon\Elegant_Elephant\Arrayable;

/**
 *
 * @package App\Takes
 */
interface TakeInertia
{
    /**
     * With callable that must returns an {@array}
     * @param callable $props
     * @return Take
     */
    public function withCallableProps(callable $props): Take;

    /**
     * @param array $props
     * @return Take
     */
    public function withArrayProps(array $props = []): Take;

    /**
     * @param Arrayable $arrayable
     * @return Take
     */
    public function withArrayableProps(Arrayable $arrayable): Take;
}
