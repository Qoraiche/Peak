<?php

namespace App\Http\Filters\User;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DownloadsSearchFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('title', 'like', '%' . $value . '%')
            ->orWhere('description', 'like', '%' . $value . '%')
            ->orWhere('release_date', 'like', '%' . $value . '%');
    }
}
