<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Settings\Frontend;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Settings\Admin\FrontCustomCodeSettings;

class CustomCodeSettingsController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request, FrontCustomCodeSettings $frontCustomCodeSettings)
    {
        return Inertia::render('Admin/Settings/Frontend/CustomCode', [
            'custom_css' => $frontCustomCodeSettings->custom_css,
            'custom_js' => $frontCustomCodeSettings->custom_js,
        ]);
    }

    /**
     * @param Request $request
     * @param FrontCustomCodeSettings $frontCustomCodeSettings
     * @return void
     */
    public function updateCss(Request $request, FrontCustomCodeSettings $frontCustomCodeSettings)
    {
        $request->validate([
            'custom_css' => 'nullable|string'
        ]);

        $frontCustomCodeSettings->custom_css = $request->get('custom_css');
        $frontCustomCodeSettings->save();
    }

    /**
     * @param Request $request
     * @param FrontCustomCodeSettings $frontCustomCodeSettings
     * @return void
     */
    public function updateJs(Request $request, FrontCustomCodeSettings $frontCustomCodeSettings)
    {
        $request->validate([
            'custom_js' => 'nullable|string'
        ]);

        $frontCustomCodeSettings->custom_js = $request->get('custom_js');

        $frontCustomCodeSettings->save();
    }
}
