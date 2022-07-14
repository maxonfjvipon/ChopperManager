<?php

namespace Modules\Selection\Support;

use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrEnvelope;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrIf;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrSticky;
use Modules\Selection\Http\Requests\RqMakeSelection;

/**
 * DN materials arrayable.
 */
final class ArrDNMaterials extends ArrEnvelope
{
    /**
     * Ctor.
     */
    public function __construct(RqMakeSelection $request)
    {
        parent::__construct(
            new ArrSticky(
                new ArrIf(
                    (bool) $request->collectors,
                    fn () => new ArrMapped(
                        $request->collectors,
                        fn (string $dnMaterial) => [
                            'dn' => ($exploded = explode(' ', $dnMaterial))[0],
                            'material' => $exploded[1],
                        ]
                    )
                ),
            )
        );
    }
}
