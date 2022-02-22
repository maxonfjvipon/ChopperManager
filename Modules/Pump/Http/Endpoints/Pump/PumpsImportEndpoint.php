<?php

namespace Modules\Pump\Http\Endpoints\Pump;

use App\Http\Controllers\Controller;
use Box\Spout\Common\Exception\IOException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Modules\Project\Http\Requests\FilesUploadRequest;
use Modules\Pump\Actions\ImportPumps\ImportPumpsAction;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pumps import endpoint.
 */
final class PumpsImportEndpoint extends Controller
{
    /**
     * @param FilesUploadRequest $request
     * @return Responsable|Response
     * @throws IOException
     * @throws AuthorizationException
     */
    public function __invoke(FilesUploadRequest $request): Responsable|Response
    {
//        return TkAuthorized::new(
//            'pump_import',
//            (new ImportPumpsAction())->execute($request->file('files'))
//        )->act($request);
        $this->authorize('pump_import');
        return (new ImportPumpsAction())->execute($request->file('files'));
    }
}
