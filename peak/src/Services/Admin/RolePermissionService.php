<?php

namespace Qoraiche\Peak\Services\Admin;

use Illuminate\Http\Request;
use Qoraiche\Peak\Http\Filters\Admin\PermissionSearchFilter;
use Qoraiche\Peak\Http\Filters\Admin\RoleSearchFilter;
use Qoraiche\Peak\Models\Permission;
use Qoraiche\Peak\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RolePermissionService
{
    public function getSearchablePaginatedPermissions(Request $request, $perPage, $searchQueryParam = 'search')
    {
        $limitPagination = (int)$request->input('limit', $perPage);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = $perPage;
        }

        return QueryBuilder::for(Permission::class)->allowedFilters([
            AllowedFilter::custom($searchQueryParam, new PermissionSearchFilter)
        ])->allowedSorts([
            'id',
            'name',
            'guard_name',
            'group'
        ])
            ->orderByDesc('id')
            ->paginate($limitPagination);
    }

    public function getSearchablePaginatedRoles(Request $request, $perPage, $searchQueryParam = 'search')
    {
        $limitPagination = (int)$request->input('limit', $perPage);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = $perPage;
        }

        return QueryBuilder::for(Role::class)->allowedFilters([
            AllowedFilter::custom($searchQueryParam, new RoleSearchFilter)
        ])->allowedSorts([
            Role::ID_COLUMN_NAME,
            Role::NAME_COLUMN_NAME,
            Role::CREATED_AT
        ])->orderByDesc(Role::ID_COLUMN_NAME)
            ->paginate($limitPagination);
    }

    /**
     * @param array $selectedColumns
     * @param string $defaultUncategorizedGroupName
     * @return mixed
     */
    public function getGroupedPermissions(array $selectedColumns = ['id', 'name', 'group'], $defaultUncategorizedGroupName = 'uncategorized')
    {
        return Permission::select($selectedColumns)
            ->get()
            ->groupBy(function ($item) use ($defaultUncategorizedGroupName) {
                return $item->group ?: $defaultUncategorizedGroupName;
            })
            ->map(function ($permissions) {
                return $permissions->pluck('name', 'id');
            });
    }
}
