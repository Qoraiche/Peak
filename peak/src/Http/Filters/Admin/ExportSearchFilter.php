<?php

namespace Qoraiche\Peak\Http\Filters\Admin;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ExportSearchFilter implements Filter
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

        $query->where("name", 'like', '%' . $value . '%')
            ->orWhere("file_path", 'like', '%' . $value . '%')
            ->orWhere("format", 'like', '%' . $value . '%')
            ->orWhere("locale", 'like', '%' . $value . '%')
            ->orWhere("file_name", 'like', '%' . $value . '%');
    }
}
