<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\LicensesSearchFilter;
use App\Models\License;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Qoraiche\Peak\Models\Ticket;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LicenseManagementController extends Controller
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

        $query = QueryBuilder::for(License::class)->allowedFilters([
            'id',
            'user_id',
            'license_key',
            'license_type',
            'expires_at',
            'created_at',
            AllowedFilter::custom('search', new LicensesSearchFilter)
        ]);

        $query = $query->where('user_id', auth()->id())->orderBy('updated_at', 'desc')
            ->paginate($limitPagination);

        return Inertia::render('Dashboard/Licenses/Index', [
            'items' => fn() => Inertia::defer(fn() => $query),
            'exportableData' => License::exportableDataColumnNames(),
        ]);
    }
}
