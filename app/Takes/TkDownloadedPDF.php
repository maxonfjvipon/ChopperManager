<?php

namespace App\Takes;

use App\Support\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TextOf;
use Maxonfjvipon\Elegant_Elephant\Text\TxtOverloadable;
use Maxonfjvipon\OverloadedElephant\Overloadable;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkDownloadedDPFTest;
use VerumConsilium\Browsershot\Facades\PDF;

/**
 * Endpoint that render pdf to download.
 * @package App\Takes
 * @see TkDownloadedDPFTest
 */
final class TkDownloadedPDF implements Take
{
    use TxtOverloadable;

    /**
     * @var Text|string $html
     */
    private string|Text $html;

    /**
     * Ctor.
     * @param string|Text $html
     */
    public function __construct(string|Text $html)
    {
        $this->html = $html;
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
