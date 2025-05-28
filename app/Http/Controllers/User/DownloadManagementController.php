<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\DownloadsSearchFilter;
use App\Http\Filters\User\LicensesSearchFilter;
use App\Models\License;
use App\Models\Release;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DownloadManagementController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $limitPagination = (int)$request->input('limit', 25);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = 25;
        }

        $query = QueryBuilder::for(Release::class)->allowedFilters([
            'title',
            'description',
            'release_date',
            'created_at',
            AllowedFilter::custom('search', new DownloadsSearchFilter)
        ]);

        $query = $query->orderBy('updated_at', 'desc')
            ->paginate($limitPagination);

        return Inertia::render('Dashboard/Downloads/Index', [
            'items' => fn() => Inertia::defer(fn() => $query),
            'exportableData' => Release::exportableDataColumnNames(),
        ]);
    }
}
