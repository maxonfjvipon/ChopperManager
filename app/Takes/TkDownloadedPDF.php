<?php

namespace App\Takes;

use App\Support\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TextOf;
use Maxonfjvipon\OverloadedElephant\Overloadable;
use Symfony\Component\HttpFoundation\Response;
use VerumConsilium\Browsershot\Facades\PDF;

/**
 * Endpoint that render pdf to download.
 * @package App\Takes
 */
final class TkDownloadedPDF implements Take
{
    use Overloadable;

    /**
     * @var Text|string $html
     */
    private string|Text $html;

    /**
     * Ctor wrap.
     * @param string|Text $html
     * @return TkDownloadedPDF
     */
    public static function new(string|Text $html): TkDownloadedPDF
    {
        return new self($html);
    }

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
        return PDF::loadHtml($this->overload([$this->html], [[
            'string',
            Text::class => fn(Text $txt) => $txt->asString()
        ]])[0])->download();
    }
}
