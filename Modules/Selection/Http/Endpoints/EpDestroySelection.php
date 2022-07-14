<?php

namespace Modules\Selection\Http\Endpoints;

use App\Http\Endpoints\TakeEndpoint;
use App\Takes\TkRedirectBack;
use App\Takes\TkWithCallback;
use Illuminate\Http\Request;
use Modules\Selection\Entities\Selection;

/**
 * Destroy selection endpoint.
 */
final class EpDestroySelection extends TakeEndpoint
{
    /**
     * Ctor.
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            new TkWithCallback(
                fn () => Selection::find($request->selection)->delete(),
                new TkRedirectBack()
            )
        );
    }
}
