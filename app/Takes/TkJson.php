<?php

namespace App\Takes;

use App\Interfaces\Take;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOverloaded;
use Maxonfjvipon\Elegant_Elephant\CastMixed;
use Maxonfjvipon\Elegant_Elephant\Text;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkJsonTest;

/**
 * Json take.
 *
 * @see TkJsonTest
 */
final class TkJson implements Take
{
    use ArrayableOverloaded;
    use CastMixed;

    /**
     * Ctor.
     *
     * @param string|array|Arrayable|Text $data
     * @param int                         $status
     * @param array                       $headers
     */
    public function __construct(
        private string|array|Arrayable|Text $data,
        private int $status = 200,
        private array $headers = []
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return response()->json($this->castMixed($this->data), $this->status, $this->headers);
    }
}
