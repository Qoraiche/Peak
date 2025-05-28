<?php

namespace Qoraiche\Peak\Http\Filters\Admin;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PermissionSearchFilter implements Filter
{
    /**
     * @param Builder $query
     * @param $value
     * @param string $property
     * @return void
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        // Ensure the value is URL-encoded
        $value = urldecode(trim($value));

        $query->where('name', 'like', "%{$value}%")
            ->orWhere('group', 'like', "%{$value}%");
    }
}
