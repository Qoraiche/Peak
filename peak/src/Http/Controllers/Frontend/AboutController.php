<?php

namespace Qoraiche\Peak\Http\Controllers\Frontend;

use Artesaos\SEOTools\Facades\SEOMeta;
use Qoraiche\Peak\Http\Controllers\Controller;
use Inertia\Inertia;

class AboutController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        SEOMeta::setTitle(__('About'));

        return Inertia::render('About');
    }
}
