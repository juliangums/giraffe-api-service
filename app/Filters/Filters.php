<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Filters
{
    /**
     * @var Request $request
     */
    public Request $request;

    /**
     * @var Builder $builder
     */
    protected Builder $builder;

    /**
     * @var array $filters
     */
    protected array $filters;

    /**
     * Filters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply filters to the builder.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        collect($this->getFilters())->each(function (?string $value, string $filter) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        });

        return $this->builder;
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        return $this->request->only(array_intersect(array_keys($this->request->all()), $this->filters));
    }
}
