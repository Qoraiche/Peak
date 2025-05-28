<?php

namespace Qoraiche\Peak\Http\Controllers\Frontend;

use Artesaos\SEOTools\Facades\SEOMeta;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Models\Page;

class HomeController extends Controller
{
    /**
     * @return Response
     */
    public function showContact()
    {
        SEOMeta::setTitle(__('Contact'));

        return Inertia::render('Contact');
    }
}
