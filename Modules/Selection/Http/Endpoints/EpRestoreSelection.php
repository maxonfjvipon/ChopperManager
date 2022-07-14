<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Http\Request;
use Modules\Selection\Entities\Selection;

/**
 * Restore selection endpoint.
 */
final class EpRestoreSelection extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => Selection::withTrashed()
                    ->find($request->selection)
                    ->restore(),
                new TkRedirectBack()
            )
        );
    }
}
