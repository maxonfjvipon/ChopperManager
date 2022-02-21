<?php

namespace Modules\Project\Http\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Takes\TkWithCallback;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Auth;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMerged;
use Modules\Project\Entities\Project;
use Modules\Project\Takes\TkRedirectedToProjectsIndex;
use Modules\Project\Http\Requests\ProjectStoreRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Projects store endpoint.
 * @package Modules\Project\Takes
 * @see Project booted method
 */
final class ProjectsStoreEndpoint extends Controller
{
    /**
     * @param ProjectStoreRequest $request
     * @return Responsable|Response
     */
    public function __invoke(ProjectStoreRequest $request): Responsable|Response
    {
        return (new TkAuthorized(
            'project_create',
            new TkWithCallback(
                fn() => Auth::user()->projects()
                    ->create(
                        (new ArrMerged(
                            $request->validated(),
                            ['status_id' => 1, 'delivery_status_id' => 1]
                        ))->asArray()
                    ),
                new TkRedirectedToProjectsIndex()
            )
        ))->act($request);
    }
}
