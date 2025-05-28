<?php

namespace Qoraiche\Peak\Http\Middleware;

use Diglactic\Breadcrumbs\Breadcrumbs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Middleware;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Models\Faq;
use Qoraiche\Peak\Models\Feature;
use Qoraiche\Peak\Models\Language;
use Qoraiche\Peak\Models\Menu as SiteMenu;
use Qoraiche\Peak\Models\Page;
use Qoraiche\Peak\Models\Post;
use Qoraiche\Peak\Models\Testimonial;
use Qoraiche\Peak\Services\Admin\BlogService;
use Qoraiche\Peak\Services\Admin\LanguageService;
use Qoraiche\Peak\Services\Billing\PlanService;
use Qoraiche\Peak\Services\Billing\SubscriptionService;
use Qoraiche\Peak\Settings\Admin\FrontendAppearanceSettings;
use Qoraiche\Peak\Settings\Admin\FrontendHeroSettings;
use Qoraiche\Peak\Settings\Admin\FrontFooterSettings;
use Qoraiche\Peak\Settings\Admin\FrontHeaderSettings;
use Qoraiche\Peak\Settings\Admin\MarketingAnnouncementSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteAuthProvidersSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteCookieSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteSecuritySettings;
use Qoraiche\Peak\Settings\Admin\WebsiteSocialLinksSettings;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\Cache;

class HandlePeakInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function share(Request $request): array
    {
        // Preload what is cheap or required immediately
        $generalSettings = app(WebsiteGeneralSettings::class);
        $securitySettings = app(WebsiteSecuritySettings::class);
//        $appLanguages = $this->getAppLanguages();
        $cookieConsentConfig = config('cookie-consent');
        $alreadyConsentedWithCookies = Cookie::has($cookieConsentConfig['cookie_name']);

//        dd(URL::asset('/images/larapeak-logo.svg'));

        return [
            ...parent::share($request),

            'translations' => Helpers::currentLocaleTranslations(),
            'locale' => Helpers::getLocale(),

            'app' => [
                'name' => config('app.name'),
            ],

            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],

            'flash' => fn() => session('flash', []),

            'peak' => [
                'theme' => current_theme_name(),
                'version' => peak_version(),
            ],

            'disabled_features' => config('peak.disabled_features'),

            'isDemo' => config('app.demo', false),

            'config' => [
                'user_roles' => config('peak.default_roles.user', ['registered', 'user']),
                'admin_roles' => config('peak.default_roles.admin', ['admin', 'super-admin']),
            ],

            'front' => [
                'logo_path' => URL::asset('/images/larapeak-logo.svg'), // Change logo path

                'hasEmailVerification' => $securitySettings->email_verification_enabled,

                'api_keys' => [
                    'tinymce' => $generalSettings->tiny_mce_api_key,
                ],

                'recaptcha' => [
                    'enabled' => false,
                    'theme' => 'light'
                ],
                'profile_management_enabled' => $securitySettings->profile_management_enabled,
                'password_update_enabled' => $securitySettings->password_update_enabled,
                'teams_management_enabled' => $securitySettings->teams_management_enabled,
                'reset_password_enabled' => $securitySettings->reset_password_enabled,
                'account_deletion_enabled' => $securitySettings->account_deletion_enabled,
                'social_login_enabled' => $securitySettings->social_login_enabled,
                'profile_browser_sessions_enabled' => $securitySettings->profile_browser_sessions_enabled,

                'site_url' => config('app.url'),
                'site_name' => config('app.name'),

                // Lazy load unread notifications count if not admin
                'unreadNotificationsCount' => fn() => !is_admin_request() ? current_user()?->unreadNotifications()->count() : 0,

                // Lazy load website settings that may query DB/config
                'tos_url' => fn() => app(WebsiteGeneralSettings::class)->tos_url,
                'privacy_policy_url' => fn() => app(WebsiteGeneralSettings::class)->privacy_policy_url,

                'contact' => fn() => [
                    'company_name' => app(WebsiteGeneralSettings::class)->company_name,
                    'company_address' => app(WebsiteGeneralSettings::class)->company_address,
                    'company_phone' => app(WebsiteGeneralSettings::class)->company_phone,
                    'contact_email_address' => app(WebsiteGeneralSettings::class)->email_address,
                ],
            ],
        ];
    }
}
