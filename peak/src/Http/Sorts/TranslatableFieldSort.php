<?php

namespace Qoraiche\Peak\Http\Sorts;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Qoraiche\Peak\Helpers;
use Spatie\QueryBuilder\Sorts\Sort;

class TranslatableFieldSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $locale = Helpers::getLocale(); // Get the current locale
        $direction = $descending ? 'DESC' : 'ASC';
        $driver = DB::getDriverName(); // Detect database type

        // Ensure the column is wrapped in backticks to avoid SQL errors
        $column = "`$property`";

        switch ($driver) {
            case 'mysql':
                $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT($column, '$.$locale')) $direction");
                break;

            case 'pgsql': // PostgreSQL
                $query->orderByRaw("($column->>'$locale') $direction");
                break;

            case 'sqlite':
                $query->orderByRaw("json_extract($column, '$.$locale') $direction");
                break;

            default:
                throw new Exception("Unsupported database driver: $driver");
        }
    }
}
