<?php

namespace Modules\Pump\Support\Pump;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filtered loaded pumps.
 */
final class FilteredPumps implements LoadedPumps
{
    /**
     * @var LoadedPumps $pumps
     */
    private LoadedPumps $origin;

    /**
     * @var ?string $search
     */
    private ?string $search;

    /**
     * Ctor wrap.
     * @param LoadedPumps $pumps
     * @param string|null $search
     * @return FilteredPumps
     */
    public static function new(LoadedPumps $pumps, string $search = null): FilteredPumps
    {
        return new self($pumps, $search);
    }

    /**
     * Ctor.
     * @param LoadedPumps $pumps
     * @param string|null $search
     */
    public function __construct(LoadedPumps $pumps, string $search = null)
    {
        $this->origin = $pumps;
        $this->search = $search;
    }

    /**
     * @inheritDoc
     */
    public function loaded(): Builder
    {
        return $this->origin->loaded()
            ->when($this->search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('article_num_main', 'like', '%' . $search . '%')
                        ->orWhere('article_num_archive', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                });
            });
    }
}
