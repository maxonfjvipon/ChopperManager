<?php

namespace App\Takes;

use App\Support\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TextOf;
use Symfony\Component\HttpFoundation\Response;
use VerumConsilium\Browsershot\Facades\PDF;

/**
 * Endpoint that render pdf to download.
 * @package App\Takes
 */
final class TkDownloadedPDF implements Take
{
    /**
     * @var Text $html
     */
    private Text $html;

    /**
     * Ctor wrap.
     * @param string $html
     * @return TkDownloadedPDF
     * @throws Exception
     */
    public static function fromString(string $html): TkDownloadedPDF
    {
        return TkDownloadedPDF::fromText(TextOf::string($html));
    }

    /**
     * Ctor wrap.
     * @param Text $html
     * @return TkDownloadedPDF
     * @throws Exception
     */
    public static function fromText(Text $html): TkDownloadedPDF
    {
        return new self($html);
    }

    /**
     * Ctor.
     * @param Text $html
     */
    private function __construct(Text $html)
    {
        $this->html = $html;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return PDF::loadHtml($this->html->asString())->download();
    }
}
