<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    protected $filterBys = [];
    protected $queryBuilder, $request;

    /**
     * __construct
     *
     * @param Request $request
     * @param array $additionalfilterBys if you wish to have more filter options other than those in request, add it here
     * @return void
     */
    public function __construct(Request $request, array $additionalfilterBys = [])
    {
        $this->request = $request;
        $this->additionalFilterBys = $additionalfilterBys;
    }

    /**
     * apply
     * apply filter to query
     * @param  queryBuilder $queryBuilder
     * @return queryBuilder $queryBuilder
     */
    public function apply($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        // filter objects by each filterBys

        // functional way 
        $this->getValidFilterBys()
            ->filter(function ($value, $filterBy) {
                return method_exists($this, $filterBy);
            })
            ->each(function ($value, $filterBy) {
                $this->$filterBy($value);
            });

        // traditional way
        // foreach ($this->getValidFilterBys() as $filterBy => $value) {
        //     if (method_exists($this, $filterBy)) {
        //         $this->$filterBy($value);
        //     }
        // }

        return $this->queryBuilder;
    }

    /**
     * getValidFilterBys
     * Get valid parameters that require filtering from reqeust and addictional array
     * @return \Illuminate\Support\Collection
     */
    public function getValidFilterBys()
    {
        return collect($this->request->only($this->filterBys))
            ->merge(collect($this->additionalFilterBys));
    }
}