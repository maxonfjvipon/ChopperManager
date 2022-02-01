<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Json take
 * @package App\Takes
 */
final class TkJson implements Take
{
    /**
     * @var callable $callback
     */
    private $callback;

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
     * @param callable $data_callback
     * @param int $status
     * @param array $headers
     * @return TkJson
     */
    public static function new(callable $data_callback, $status = 200, array $headers = []): TkJson
    {
        return new self($data_callback, $status, $headers);
    }

    /**
     * Ctor.
     * @param callable $data_callback
     * @param int $status
     * @param array $headers
     */
    public function __construct(callable $data_callback, $status = 200, array $headers = [])
    {
        $this->callback = $data_callback;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * @inheritDoc
     */
    public function act(Request $request = null): Responsable|Response
    {
        return response()->json(call_user_func($this->callback), $this->status, $this->headers);
    }
}
