<?php

namespace Qoraiche\Peak\Http\Controllers\User;

use Qoraiche\Peak\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Dashboard/Index');
    }
}
