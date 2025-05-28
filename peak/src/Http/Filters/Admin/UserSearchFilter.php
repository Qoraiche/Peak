<?php

namespace Qoraiche\Peak\Http\Filters\Admin;

use Illuminate\Database\Eloquent\Builder;
use Qoraiche\Peak\Models\User;
use Spatie\QueryBuilder\Filters\Filter;

class UserSearchFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = urldecode(trim($value));
        
        $query->where(function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
                ->orWhere(User::EMAIL_COLUMN_NAME, 'like', '%' . $value . '%')
                ->orWhere(User::ID_COLUMN_NAME, 'like', '%' . $value . '%');
        });
    }
}
