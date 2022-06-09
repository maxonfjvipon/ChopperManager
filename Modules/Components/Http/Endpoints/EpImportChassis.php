<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Requests\RqUploadFiles;
use Illuminate\Http\RedirectResponse;
use Modules\Components\Actions\Import\AcImportChassis;

final class EpImportChassis extends Controller
{
    /**
     * @param RqUploadFiles $request
     * @return RedirectResponse
     */
    public function __invoke(RqUploadFiles $request): RedirectResponse
    {
        return (new AcImportChassis($request->file('files')))->execute();
    }
}
