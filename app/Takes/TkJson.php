<?php

namespace App\Takes;

use App\Support\Take;
use Closure;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\OverloadedElephant\Overloadable;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\Takes\TkJsonTest;

/**
 * Json take
 * @package App\Takes
 * @see TkJsonTest
 */
final class TkJson implements Take
{
    use Overloadable;

    /**
     * @var string|array|callable|Arrayable|Text|JsonResource $data
     */
    private $data;

    /**
     * @var int $status
     */
    private int $status;

    /**
     * @var array
     */
    private array $headers;

    /**
     * Ctor.
     * @param string|array|callable|Arrayable|Text|JsonResource $data
     * @param int $status
     * @param array $headers
     */
    public function __construct(
        string|array|callable|Arrayable|Text|JsonResource $data,
        int                                               $status = 200,
        array                                             $headers = []
    )
    {
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return response()->json($this->overload([$this->data], [[
            'boolean',
            'string',
            'array',
            'callable' => fn(callable $callback) => call_user_func($callback),
            Closure::class => fn(Closure $closure) => call_user_func($closure),
            Arrayable::class => fn(Arrayable $arr) => $arr->asArray(),
            JsonResource::class => fn(JsonResource $resource) => $resource->toArray($request),
            Text::class => fn(Text $txt) => $txt->asString()
        ]])[0], $this->status, $this->headers);
    }
}
