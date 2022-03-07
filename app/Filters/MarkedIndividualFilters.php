<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class MarkedIndividualFilters extends Filters
{
    /**
    * All the filters available to filter through
    *
    * @var array
    */
    protected array $filters = [
        'max_age', 'min_age', 'species', 'gender','location',
    ];

    public function min_age(?int $min): Builder
    {
        if (is_null($min)) {
            return $this->builder;
        }

        return $this->builder->whereHas('encounters', fn(Builder $query) => $query->where('AGE', '>=', $min));
    }

    public function max_age(?int $max): Builder
    {
        if (is_null($max)) {
            return $this->builder;
        }

        return $this->builder->whereHas('encounters', fn(Builder $query) => $query->where(fn(Builder $q) => $q->where('AGE', '<=', $max)->orWhereNull('AGE')));
    }

    public function species(?string $species): Builder
    {
        if (is_null($species)) {
            return $this->builder;
        }

        return $this->builder->whereHas('encounters', fn(Builder $query) => $query->where('SPECIFICEPITHET', $species));
    }

    /**
     * Filter by gender.
     *
     * @param string|null $gender
     * @return Builder
     */
    public function gender(?string $gender): Builder
    {
        if (is_null($gender)) {
            return $this->builder;
        }

        $genders = [
            'm' => ['m', 'M', 'male', 'Male', 'MALE'],
            'f' => ['f', 'F', 'female', 'Female', 'FEMALE'],
            'u' => ['u', 'U', 'unknown', 'Unknown', 'UNKNOWN', null],
        ];

        return $this->builder->whereIn('SEX', $genders[$gender]);
    }

    public function location(?string $location)
    {
        if (is_null($location)) {
            return $this->builder;
        }

        return $this->builder->whereHas('encounters', fn(Builder $query) => $query->where('LOCATIONID', $location));
    }
}
