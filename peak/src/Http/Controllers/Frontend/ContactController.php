<?php

namespace Qoraiche\Peak\Http\Controllers\Frontend;

use Artesaos\SEOTools\Facades\SEOMeta;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        SEOMeta::setTitle(__('Contact'));

        return Inertia::render('Contact');
    }
}
