<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Settings\System;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Qoraiche\Peak\Http\Controllers\Controller;

class LogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Admin/Settings/System/Log');
    }
}
