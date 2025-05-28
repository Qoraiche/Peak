<?php

namespace Qoraiche\Peak\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Qoraiche\Peak\Http\Controllers\Controller;

class MarkAllNotificationsAsReadController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
    }
}
