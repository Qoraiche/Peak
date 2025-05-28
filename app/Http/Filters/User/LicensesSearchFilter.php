<?php

namespace App\Http\Filters\User;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class LicensesSearchFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('id', $value)
            ->orWhere('user_id', $value)
            ->orWhere('license_key', 'like', '%' . $value . '%')
            ->orWhere('plan', 'like', '%' . $value . '%')
            ->orWhere('license_type', 'like', '%' . $value . '%');
    }
}
