<?php

namespace App\Endpoints;

use App\Support\Html;
use App\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use VerumConsilium\Browsershot\Facades\PDF;

/**
 * Endpoint that render pdf to download.
 * @package App\Endpoints
 */
class PDFDownloadedFromHtmlEndpoint implements Renderable
{
    /**
     * @var Html $html
     */
    private Html $html;

    /**
     * Ctor.
     * @param Html $html
     */
    public function __construct(Html $html)
    {
        $this->html = $html;
    }

    /**
     * @inheritDoc
     */
    public function render(Request $request = null): Responsable|Response
    {
        return PDF::loadHtml($this->html->asString())->download();
    }
}
