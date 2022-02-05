<?php

namespace App\Takes;

use App\Support\Take;
use Closure;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maxonfjvipon\OverloadedElephant\Overloadable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Json take
 * @package App\Takes
 */
final class TkJson implements Take
{
    use Overloadable;

    /**
     * @var callable|string|array $data
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
     * Ctor wrap.
     * @param string|array|callable $data
     * @param int $status
     * @param array $headers
     * @return TkJson
     */
    public static function new(string|array|callable $data, $status = 200, array $headers = []): TkJson
    {
        return new self($data, $status, $headers);
    }

    /**
     * Ctor.
     * @param string|array|callable $data
     * @param int $status
     * @param array $headers
     */
    public function __construct(string|array|callable $data, $status = 200, array $headers = [])
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
            Closure::class => fn(Closure $closure) => call_user_func($closure)
        ]])[0], $this->status, $this->headers);
    }
}
