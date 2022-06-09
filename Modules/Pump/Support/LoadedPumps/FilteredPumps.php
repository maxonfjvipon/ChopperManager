<?php

namespace Modules\Pump\Support\LoadedPumps;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Support\LazyLoadedPumps\LazyLoadedPumps;

/**
 * Filtered loaded pumps.
 */
final class FilteredPumps implements LoadedPumps, LazyLoadedPumps
{
    /**
     * Ctor.
     * @param LoadedPumps|LazyLoadedPumps $origin
     * @param string|null $search
     */
    public function __construct(private LoadedPumps|LazyLoadedPumps $origin, private ?string $search = null) {}

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
                return stristr($this->lowered($pump->article_num_main), $this->lowered($this->search)) ||
                    stristr($this->lowered($pump->article_num_archive), $this->lowered($this->search)) ||
                    stristr($this->lowered($pump->name), $this->lowered($this->search));
            });
        });
    }

    /**
     * @param string $str
     * @return string|false
     */
    private function lowered(string $str): string|false
    {
        return mb_strtolower($str, 'UTF-8');
    }
}
