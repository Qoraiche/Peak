<?php

namespace Qoraiche\Peak\Services\Admin;

use Illuminate\Http\Request;
use Qoraiche\Peak\Http\Filters\Admin\ExportSearchFilter;
use Qoraiche\Peak\Models\Export;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExportService
{
    /**
     * @param Request $request
     * @param $withAnyTag
     * @param $perPage
     * @param string $searchQueryParam
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFrontSearchablePaginatedExports(Request $request, $withAnyTag = null, $perPage = 25, string $searchQueryParam = 'search')
    {
        $limitPagination = (int)$request->input('limit', $perPage);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = $perPage;
        }

        return QueryBuilder::for(Export::class)
            ->allowedFilters([
                AllowedFilter::custom($searchQueryParam, new ExportSearchFilter)
            ])
            ->allowedSorts([
                'id',
                'name',
                'file_name',
                'format',
                'locale',
                'status',
                Export::CREATED_AT,
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($limitPagination);
    }
}
