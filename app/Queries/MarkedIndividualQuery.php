<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\MarkedIndividualFilters;

/**
 * @extends Builder<MarkedIndividual>
 */
class MarkedIndividualQuery extends Builder
{
    public function approved(): self
    {
        return $this->whereHas('encounters', fn(Builder $query) => $query->where('STATE', 'approved'));
    }

    /**
     * Filter the query.
     *
     * @param MarkedIndividualFilters $filters
     * @return Builder
     */
    public function filter(MarkedIndividualFilters $filters): self
    {
        return $filters->apply($this);
    }
}
