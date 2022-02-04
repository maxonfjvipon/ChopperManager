<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Modules\Core\Http\Requests\ProjectStoreRequest;
use Symfony\Component\HttpFoundation\Response;

// hello world!

/**
 * Projects store endpoint.
 * @package Modules\Core\Takes
 */
final class ProjectsStoreEndpoint extends Controller
{
    /**
     * @param ProjectStoreRequest $request
     * @return Responsable|Response
     */
    public function __invoke(ProjectStoreRequest $request): Responsable|Response
    {
        return TkAuthorized::new(
            'project_create',
            TkWithCallback::new(
                fn() => Auth::user()->projects()
                    ->create(ArrMerged::new(
                        $request->validated(),
                        ['status_id' => 1, 'delivery_status_id' => 1]
                    )->asArray()),
                TkRedirectedToProjectsIndex::new()
            )
        )->act($request);
    }
}
