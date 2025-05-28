<?php

namespace Qoraiche\Peak\Http\Filters\Admin;

use Illuminate\Database\Eloquent\Builder;
use Qoraiche\Peak\Models\Role;
use Spatie\QueryBuilder\Filters\Filter;

class RoleSearchFilter implements Filter
{
    /**
     * @param Builder $query
     * @param $value
     * @param string $property
     * @return void
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = urldecode(trim($value));

        $query->where(Role::NAME_COLUMN_NAME, 'like', '%' . $value . '%')
            ->orWhere(Role::ID_COLUMN_NAME, 'like', '%' . $value . '%');
    }
}
