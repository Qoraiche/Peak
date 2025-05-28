<?php

namespace Qoraiche\Peak\Http\Controllers\User;

use Illuminate\Http\Request;
use Qoraiche\Peak\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the general profile settings screen.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        return Inertia::render('Dashboard/Account/Profile');
    }
}
