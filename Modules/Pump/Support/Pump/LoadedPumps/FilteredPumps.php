<?php

namespace Modules\Pump\Support\Pump\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Pump\Support\Pump\LazyLoadedPumps\LazyLoadedPumps;

/**
 * Filtered loaded pumps.
 */
final class FilteredPumps implements LoadedPumps, LazyLoadedPumps
{
    /**
     * @var LoadedPumps|LazyLoadedPumps $pumps
     */
    private LoadedPumps|LazyLoadedPumps $origin;

    /**
     * @var ?string $search
     */
    private ?string $search;

    /**
     * Ctor wrap.
     * @param LoadedPumps|LazyLoadedPumps $pumps
     * @param string|null $search
     * @return FilteredPumps
     */
    public static function new(LoadedPumps|LazyLoadedPumps $pumps, string $search = null): FilteredPumps
    {
        return new self($pumps, $search);
    }

    /**
     * Ctor.
     * @param LoadedPumps|LazyLoadedPumps $pumps
     * @param string|null $search
     */
    public function __construct(LoadedPumps|LazyLoadedPumps $pumps, string $search = null)
    {
        $this->origin = $pumps;
        $this->search = $search;
    }

    /**
     * @inheritDoc
     */
    public function loaded(): Builder
    {
        return $this->filtered($this->origin->loaded());
    }

    public function lazyLoaded(): Collection
    {
        return $this->filtered($this->origin->lazyLoaded());
    }

    private function filtered(Builder|Collection $query): Collection|Builder
    {
        return $query->when($this->search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('article_num_main', 'like', '%' . $search . '%')
                    ->orWhere('article_num_archive', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        });
    }
}
