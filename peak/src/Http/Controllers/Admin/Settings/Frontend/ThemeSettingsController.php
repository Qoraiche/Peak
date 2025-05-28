<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Settings\Frontend;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Events\ThemeChanged;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Requests\Admin\Theme\ActivateThemeRequest;
use Qoraiche\Peak\Services\ThemeService;
use Qoraiche\Peak\Settings\Admin\AppearanceThemeSettings;

class ThemeSettingsController extends Controller
{
    /**
     * @param AppearanceThemeSettings $appearanceThemeSettings
     * @param ThemeService $themeService
     */
    public function __construct(private AppearanceThemeSettings $appearanceThemeSettings,
                                private ThemeService            $themeService)
    {
    }

    /**
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function settings(): Response
    {
        return Inertia::render('Admin/Settings/Frontend/Theme', [
            'themes' => $this->themeService->getAllThemes()
        ]);
    }

    /**
     * @param ActivateThemeRequest $activateThemeRequest
     * @return void
     */
    public function update(ActivateThemeRequest $activateThemeRequest): void
    {
        $theme = $activateThemeRequest->input('theme');

        $this->appearanceThemeSettings->current_theme_name = $theme;
        $this->appearanceThemeSettings->save();

        event(new ThemeChanged($theme));

        set_app_environment_value([
            'VITE_APP_THEME' => $theme
        ], true);
    }
}
