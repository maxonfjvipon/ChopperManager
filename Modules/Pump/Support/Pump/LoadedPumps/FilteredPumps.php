<?php

namespace Modules\Pump\Support\Pump\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Pump\Entities\Pump;
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
        return $this->origin->loaded()->when($this->search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('article_num_main', 'like', '%' . $search . '%')
                    ->orWhere('article_num_archive', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        });
    }

    /**
     * @return Collection
     */
    public function lazyLoaded(): Collection
    {
        return $this->origin->lazyLoaded()->when($this->search, function (Collection $pumps) {
            return $pumps->filter(function (Pump $pump) {
                return stristr($pump->article_num_main, $this->search) ||
                    stristr($pump->article_num_archive, $this->search) ||
                    stristr($pump->name, $this->search);
            });
        });
    }
}
