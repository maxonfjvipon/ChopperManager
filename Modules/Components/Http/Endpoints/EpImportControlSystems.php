<?php

namespace Modules\Components\Http\Endpoints;

use App\Http\Controllers\Controller;
use App\Http\Requests\RqUploadFiles;
use Exception;
use Illuminate\Http\RedirectResponse;
use Modules\Components\Actions\Import\ControlSystems\AcImportControlSystems;

final class EpImportControlSystems extends Controller
{
    /**
     * @param RqUploadFiles $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function __invoke(RqUploadFiles $request): RedirectResponse
    {
        return (new AcImportControlSystems($request->file('files')))->execute();
    }
}
