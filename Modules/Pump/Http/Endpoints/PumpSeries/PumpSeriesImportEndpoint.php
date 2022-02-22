<?php

namespace Modules\Pump\Http\Endpoints\PumpSeries;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Modules\Project\Http\Requests\FilesUploadRequest;
use Modules\Pump\Actions\ImportPumpSeriesAction;

final class PumpSeriesImportEndpoint extends Controller
{
    /**
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function __invoke(FilesUploadRequest $request): RedirectResponse
    {
        $this->authorize('series_import');
        return (new ImportPumpSeriesAction($request->file('files')))->execute();
    }
}
