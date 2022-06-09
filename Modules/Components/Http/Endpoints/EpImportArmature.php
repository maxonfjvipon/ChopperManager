<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Requests\RqUploadFiles;
use Illuminate\Http\RedirectResponse;
use Modules\Components\Actions\Import\AcImportArmature;

final class EpImportArmature extends Controller
{
    /**
     * @param RqUploadFiles $request
     * @return RedirectResponse
     */
    public function __invoke(RqUploadFiles $request): RedirectResponse
    {
        return (new AcImportArmature($request->file('files')))->execute();
    }
}
