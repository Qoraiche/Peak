<?php

namespace Qoraiche\Peak\Http\Filters\Admin;

use Illuminate\Database\Eloquent\Builder;
use Qoraiche\Peak\Models\User;
use Spatie\QueryBuilder\Filters\Filter;

class TeamSearchFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = urldecode(trim($value));

        $query->where(function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
                ->orWhereHas('owner', function ($q) use ($value) {
                    $q->where('name', 'like', '%' . $value . '%')
                        ->orWhere('email', 'like', '%' . $value . '%');
                })
                ->orWhereHas('users', function ($q) use ($value) {
                    $q->where('name', 'like', '%' . $value . '%')
                        ->orWhere('email', 'like', '%' . $value . '%');
                });
        });
    }
}
