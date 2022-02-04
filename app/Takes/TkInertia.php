<?php

namespace App\Takes;

use App\Support\Take;
use Closure;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\OverloadedElephant\Overloadable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inertia endpoint.
 * // TODO: make overridable
 * @package App\Takes
 */
final class TkInertia implements Take
{
    use Overloadable;

    /**
     * @var string|Text $component
     */
    private string|Text $component;

    /**
     * @var array|callable|Arrayable $props
     */
    private $props;

    /**
     * @param string|Text $component
     * @param array|callable|Arrayable $props
     * @return TkInertia
     */
    public static function new(string|Text $component, array|callable|Arrayable $props = []): TkInertia
    {
        return new self($component, $props);
    }

    /**
     * Ctor.
     * @param string|Text $component
     * @param array|callable|Arrayable $props
     */
    public function __construct(string|Text $component, array|callable|Arrayable $props)
    {
        $this->component = $component;
        $this->props = $props;
    }

    /**
     * @param Request|null $request
     * @return Responsable|Response
     * @throws Exception
     */
    public function act(Request $request = null): Responsable|Response
    {
        return Inertia::render(...self::overload([$this->component, $this->props], [[
            'string',
            Text::class => fn(Text $txt) => $txt->asString()
        ], [
            'array',
            Arrayable::class => fn(Arrayable $arr) => $arr->asArray(),
            'callable' => fn(callable $callback) => call_user_func($callback),
            Closure::class => fn(Closure $closure) => call_user_func($closure)
        ]]));
    }
}