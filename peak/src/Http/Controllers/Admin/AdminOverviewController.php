<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Qoraiche\Peak\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Services\Admin\AdminOverviewService;

class AdminOverviewController extends Controller
{
    /**
     * @param AdminOverviewService $adminOverviewService
     * @return Response
     */
    public function index(AdminOverviewService $adminOverviewService)
    {
        return Inertia::render('Admin/Dashboard', [
            ...$adminOverviewService->getCardAndWidgetsData(),
        ]);
    }
}
