<?php

namespace App\Takes;

use App\Interfaces\Take;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tests\Unit\Takes\TkAuthorizedTest;

/**
 * Endpoint that authorize user abilities.
 *
 * @see TkAuthorizedTest
 */
final class TkAuthorize extends TkEnvelope
{
    use AuthorizesRequests;

    /**
     * Ctor.
     */
    public function __construct(private string $ability, private Take $origin)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => $this->authorize($this->ability),
                $this->origin
            )
        );
    }
}
