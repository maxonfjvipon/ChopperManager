<?php

namespace App\Http\Controllers;

use App\Actions\ImportPumpsAction;
use App\Actions\ImportPumpsPriceListsAction;
use App\Http\Requests\FilesUploadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PumpsPriceListsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param FilesUploadRequest $request
     * @return RedirectResponse
     */
    public function __invoke(FilesUploadRequest $request): RedirectResponse
    {
        return (new ImportPumpsPriceListsAction())->execute($request->file('files'));
    }
}
