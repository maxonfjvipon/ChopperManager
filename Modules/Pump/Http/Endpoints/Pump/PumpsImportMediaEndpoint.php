<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use App\Support\TenantStorage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Project\Http\Requests\MediaUploadRequest;

/**
 * Pumps import media endpoint.
 */
final class PumpsImportMediaEndpoint extends Controller
{
    /**
     * @param MediaUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function __invoke(MediaUploadRequest $request): RedirectResponse
    {
        $this->authorize('pump_import_media');
        $tenantStorage = new TenantStorage();
        foreach ($request->file('files') as $image)
            if (!$tenantStorage->putFileTo($request->folder, $image))
                Redirect::back()->with('error', 'Media were not imported. Please contact to administrator');
        return Redirect::back()->with('success', 'Media were imported successfully to directory: ' . $request->folder);

    }
}
