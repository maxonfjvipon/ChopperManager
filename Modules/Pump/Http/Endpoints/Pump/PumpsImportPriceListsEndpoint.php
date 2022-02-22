<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Modules\Project\Http\Requests\FilesUploadRequest;
use Modules\Pump\Actions\ImportPumpsPriceListsAction;

/**
 * Pumps import price lists endpoint.
 */
final class PumpsImportPriceListsEndpoint extends Controller
{
    /**
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function __invoke(FilesUploadRequest $request): RedirectResponse
    {
        $this->authorize('price_list_import');
        return (new ImportPumpsPriceListsAction($request->file('files')))->execute();
    }
}
