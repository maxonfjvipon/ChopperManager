<?php


namespace Modules\Pump\Services\Pumps\PumpType;

use Illuminate\Database\Eloquent\Builder;
use Modules\Pump\Contracts\Pumps\PumpableTypePumpServiceContract;

abstract class PumpableTypePumpService implements PumpableTypePumpServiceContract
{
    abstract protected function pumpsQuery(): Builder;

    public function pumpsBuilder($searchFilter): Builder
    {
        return $this->pumpsQuery()
            ->when($searchFilter, function ($query, $searchField) {
                return $query->where(function ($query) use ($searchField) {
                    $query->where('article_num_main', 'like', '%' . $searchField . '%')
                        ->orWhere('article_num_reserve', 'like', '%' . $searchField . '%')
                        ->orWhere('article_num_archive', 'like', '%' . $searchField . '%')
                        ->orWhere('name', 'like', '%' . $searchField . '%');
                });
            });
    }
}
