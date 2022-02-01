<?php

namespace Modules\Core\Endpoints;

use App\Takes\TkAuthorized;
use App\Http\Controllers\Controller;
use App\Support\Take;
use App\Takes\TkWithCallback;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Takes\TkCreatedProject;
use Modules\Core\Takes\TkRedirectedToProjectsIndex;
use Modules\Core\Http\Requests\ProjectStoreRequest;
use Symfony\Component\HttpFoundation\Response;

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
                fn() => Auth::user()->projects()->create($request->validated()),
                TkRedirectedToProjectsIndex::new()
            )
        )->act($request);
    }
}
