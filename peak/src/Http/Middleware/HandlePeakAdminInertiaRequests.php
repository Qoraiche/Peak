<?php

namespace Qoraiche\Peak\Http\Middleware;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Middleware;
use Qoraiche\Peak\Facades\Card;
use Qoraiche\Peak\Facades\Menu;
use Qoraiche\Peak\Facades\SearchResource;
use Qoraiche\Peak\Facades\Widget;
use Qoraiche\Peak\Models\Language;
use Qoraiche\Peak\Models\Menu as SiteMenu;
use Qoraiche\Peak\Models\Page;
use Qoraiche\Peak\Settings\Admin\SellingSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteSecuritySettings;
use Tighten\Ziggy\Ziggy;

class HandlePeakAdminInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'admin';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $breadcrumbs = $this->breadcrumbs(); // Might be expensive, consider lazy load too

        $generalSettings = app(WebsiteGeneralSettings::class);

        return [
            ...parent::share($request),

            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],

            'admin' => [
                'show_made_by_peak' => config('peak.settings.show_made_by_peak', true),

                'api_keys' => [
                    'tinymce' => $generalSettings->tiny_mce_api_key,
                ],

                'logo_path' => URL::asset('/images/larapeak-logo.svg'), // Change logo path

                // Lazy load breadcrumbs
                'breadcrumbs' => fn() => $breadcrumbs->toArray(),

                // Lazy load search resources
                'searchResources' => fn() => SearchResource::all(),

                // Lazy load menus
                'menus' => fn() => [
                    'sidebar' => Menu::get('admin_main_menu'),
                    'settings_sidebar' => Menu::get('admin_settings_menu'),
                    'profile_menu' => Menu::get('admin_user_menu'),
                ],

//                'currentLanguageCollection' => get_language_collection(),

                // appLanguages might be cheap, but you can lazy load if needed
//                'appLanguages' => $appLanguages,

                // Lazy load widgets and cards (DB queries)
                'widgets' => fn() => Widget::all('default'),
                'header_widgets' => fn() => Widget::all('header'),
                'cards' => fn() => Card::all(),
            ],
        ];
    }

    /**
     * @return Collection
     */
    private function breadcrumbs()
    {
        // Get the current route name
        $routeName = request()->route()?->getName();

        // Get the route parameters (if any)
        $routeParameters = request()->route()?->parameters();

        try {
            // Check if breadcrumbs exist for the current route
            if (Breadcrumbs::exists($routeName)) {
                // Pass parameters to the breadcrumb generation (like a Page model, etc.)
                return Breadcrumbs::generate($routeName, ...$routeParameters);
            }

            return collect(); // Return empty if no breadcrumbs exist

        } catch (Exception $e) {
            return collect(); // Return empty if any error occurs
        }
    }
}
