<?php

namespace App\Takes;

use App\Support\Take;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maxonfjvipon\Elegant_Elephant\Arrayable;
use Maxonfjvipon\Elegant_Elephant\Text;
use Maxonfjvipon\Elegant_Elephant\Text\TextOf;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inertia endpoint.
 * @package App\Takes
 */
final class TkInertia
{
    /**
     * Ctor wrap.
     * @param string $component
     * @return TakeInertia|Take
     */
    public static function withStrComponent(string $component): TakeInertia|Take
    {
        return TkInertia::withTxtComponent(TextOf::string($component));
    }

    /**
     * @param Text $component
     * @return TakeInertia|Take
     */
    public static function withTxtComponent(Text $component): TakeInertia|Take
    {
        return new class($component) implements TakeInertia, Take {
            /**
             * @var Text $component
             */
            private Text $component;

            /**
             * Ctor.
             * @param Text $component
             */
            public function __construct(Text $component)
            {
                $this->component = $component;
            }

            /**
             * @param Arrayable|null $arrayable
             * @return Take
             */
            public function withArrayableProps(?Arrayable $arrayable): Take
            {
                return $this->withCallableProps(fn() => $arrayable->asArray());
            }

            /**
             * @param array $props
             * @return Take
             */
            public function withArrayProps(array $props = []): Take
            {
                return $this->withCallableProps(fn() => $props);
            }

            /**
             * @param callable $props
             * @return Take
             */
            public function withCallableProps(callable $props): Take
            {
                return new class ($this->component, $props) implements Take {
                    /**
                     * @var Text $component
                     */
                    private Text $component;

                    /**
                     * @var  $props
                     */
                    private $props;

                    /**
                     * Ctor.
                     * @param Text $component
                     * @param $props
                     */
                    public function __construct(Text $component, callable $props)
                    {
                        $this->component = $component;
                        $this->props = $props;
                    }

                    /**
                     * @param Request|null $request
                     * @return Responsable|Response
                     */
                    public function act(Request $request = null): Responsable|Response
                    {
                        return Inertia::render($this->component->asString(), call_user_func($this->props));
                    }
                };
            }

            public function act(Request $request = null): Responsable|Response
            {
                return Inertia::render($this->component->asString(), []);
            }
        };
    }
}
