<?php

namespace Qoraiche\Peak\Http\Controllers\Frontend;

use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;

class FrontController extends Controller
{
    /**
     * @param WebsiteGeneralSettings $websiteGeneralSettings
     * @return Response
     */
    public function __invoke(
        WebsiteGeneralSettings $websiteGeneralSettings
    )
    {
        return Inertia::render('Index', [
            'title' => $websiteGeneralSettings->getTranslatable('site_title'),
        ]);
    }
}
