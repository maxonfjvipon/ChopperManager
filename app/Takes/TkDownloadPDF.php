<?php

namespace App\Takes;

use App\Interfaces\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TxtOverloadable;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkDownloadedDPFTest;
use VerumConsilium\Browsershot\Facades\PDF;

/**
 * Endpoint that render pdf to download.
 * @package App\Takes
 * @see TkDownloadedDPFTest
 */
final class TkDownloadPDF implements Take
{
    use TxtOverloadable;

    /**
     * Ctor.
     * @param string|Text $html
     */
    public function __construct(private string|Text $html)
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return PDF::loadHtml($this->firstTxtOverloaded($this->html))->download();
    }
}
