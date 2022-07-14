<?php

namespace Modules\Project\Actions;

use App\Interfaces\InvokableAction;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Http\Requests\RqStoreProject;

/**
 * Store project action.
 */
final class AcStoreProject implements InvokableAction
{
    /**
     * Ctor.
     */
    public function __construct(private RqStoreProject $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(): void
    {
        Auth::user()->projects()->create($this->request->projectProps());
    }
}
