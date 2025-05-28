<?php

use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Models\Language;
use Qoraiche\Peak\Settings\Admin\AppearanceThemeSettings;
use Qoraiche\Peak\Settings\Admin\FrontCustomCodeSettings;
use Qoraiche\Peak\Settings\Admin\FrontendLogoSettings;
use Qoraiche\Peak\Settings\Admin\SellingPaddlePaymentSettings;
use Qoraiche\Peak\Settings\Admin\SellingStripePaymentSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteAnalyticsSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteFileStorageSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteMailSettings;
use Qoraiche\Peak\Settings\Admin\WebsitePwaSettings;
use Spatie\TranslationLoader\LanguageLine;

if (!function_exists('peak_path')) {
    function peak_path()
    {
        return base_path('peak');
    }
}

if (!function_exists('peak_version')) {
    function peak_version()
    {
        $peak = File::json(peak_path() . '/peak.json');

        return \Illuminate\Support\Arr::get($peak, 'version', 'unknown');
    }
}

if (!function_exists('to_minor_units')) {
    function to_minor_units($dollars): int
    {
        // Ensure it's a valid numeric string or float
        $dollars = (float)filter_var($dollars, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Multiply by 100 and convert to an integer
        return (int)bcmul($dollars, '100', 0);
    }
}

if (!function_exists('get_custom_css_code')) {
    /**
     * @return string|null
     */
    function get_custom_css_code(): ?string
    {
        return app()->make(FrontCustomCodeSettings::class)->custom_css;
    }
}

if (!function_exists('get_custom_js_code')) {
    /**
     * @return string|null
     * @throws BindingResolutionException
     */
    function get_custom_js_code(): ?string
    {
        return app()->make(FrontCustomCodeSettings::class)->custom_js;
    }
}

if (!function_exists('feature_enabled')) {
    /**
     * Check if app feature enabled
     *
     * Flat feature list (like 'referrals')
     * Grouped features (like 'auth' => ['2fa', 'sessions'])
     * Dot notation (feature_enabled('auth.2fa'))
     * Still works with simple 'feature_enabled('referrals')'
     *
     * @param string $key
     * @return bool
     */
    function feature_enabled(string $key): bool
    {
        $features = config('peak.features', []);

        // Handle dot notation (e.g. auth.2fa)
        $parts = explode('.', $key);
        $current = $features;

        foreach ($parts as $part) {
            if (is_array($current) && array_key_exists($part, $current)) {
                $current = $current[$part];
            } elseif (is_array($current) && in_array($part, $current)) {
                return true;
            } else {
                return false;
            }
        }

        return (bool)$current;
    }
}

if (!function_exists('current_session_locale')) {
    /**
     * @return string
     */
    function current_session_locale(): string
    {
        return auth()->check() ? auth()->user()->preferredLocale()
            : session('locale', app()->getLocale());
    }
}

if (!function_exists('current_user')) {
    /**
     * @return \App\Models\User|\Qoraiche\Peak\Models\User|Authenticatable|null
     */
    function current_user()
    {
        return auth()->user();
    }
}

if (!function_exists('website_general_setting')) {
    /**
     * @param string $setting
     * @param bool|null $translatable
     * @return string|null
     */
    function website_general_setting(string $setting, ?bool $translatable = false): ?string
    {
        if ($translatable) {
            return app(WebsiteGeneralSettings::class)->getTranslatable($setting);
        }

        return app(WebsiteGeneralSettings::class)->{$setting};
    }
}

if (!function_exists('is_admin_request')) {
    if (!function_exists('is_admin_request')) {
        function is_admin_request(): bool
        {
            return Route::currentRouteName()
                ? str_starts_with(Route::currentRouteName(), 'admin.')
                : false;
        }
    }
}

if (!function_exists('get_default_locale_code')) {
    /**
     * @return string
     */
    function get_default_locale_code(): string
    {
        return config('app.fallback_locale');
    }
}

if (!function_exists('get_file_storage_disk_name')) {
    /**
     * @return string
     */
    function get_file_storage_disk_name(): string
    {
        return app(WebsiteFileStorageSettings::class)->disk ?? config('filesystems.default');
    }
}

if (!function_exists('global_currencies')) {
    function global_currencies()
    {
        $currencies = new ISOCurrencies();

        return collect($currencies)->map(function (Currency $currency) {
            return $currency->getCode();
        })->toArray();
    }
}

if (!function_exists('global_timezones')) {
    function global_timezones(): array
    {
        return DateTimeZone::listIdentifiers();
    }
}

if (!function_exists('app_date_time_format')) {
    function app_date_time_format()
    {
        return app(WebsiteGeneralSettings::class)->getTranslatable('date_time_format');
    }
}

if (!function_exists('app_date_format')) {
    function app_date_format()
    {
        return app(WebsiteGeneralSettings::class)->getTranslatable('date_format');
    }
}

if (!function_exists('set_app_environment_value')) {
    /**
     * @param array $values
     * @return bool
     */
    function set_app_environment_value(array $values, $clearAllCache = false): bool
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str = trim($str);

        foreach ($values as $envKey => $envValue) {
            // Quote the value if it contains spaces or special characters
            if (preg_match('/[\s#=]/', $envValue)) {
                $envValue = '"' . addslashes($envValue) . '"';
            }

            $str .= "\n"; // ensure newline at the end for accurate position detection
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);

            if ($keyPosition === false || $endOfLinePosition === false) {
                // Append the new key=value pair
                $str .= "{$envKey}={$envValue}";
            } else {
                // Replace the existing line
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }

        $str = trim($str) . "\n";

        $result = file_put_contents($envFile, $str) !== false;

        if ($result) {
            if ($clearAllCache) {
                cache()->flush();
            } else {
                Artisan::call('config:clear');
            }
        }

        return $result;
    }
}

if (!function_exists('current_locale_translations')) {
    /**
     * @return array|string
     */
    function current_locale_translations($locale = null): array|string
    {
        // current locale translations
        $locale = $locale ?? current_session_locale(); // Get the current locale

        App::setLocale($locale);

        // Load JSON translations from file
        return Lang::get('*');
    }
}

if (!function_exists('get_app_admins')) {
    /**
     * @return Collection
     */
    function get_app_admins(): Collection
    {
        return User::role(config('peak.default_roles.admin'))->get();
    }
}

if (!function_exists('is_app_mail_configured')) {
    /**
     * @return bool
     */
    function is_app_mail_configured(): bool
    {
        $appMailSettings = app(WebsiteMailSettings::class);

        return $appMailSettings->host !== null && $appMailSettings->username !== null && $appMailSettings->password !== null;
    }
}

if (!function_exists('seo_generate')) {
    function seo_generate()
    {
        /** @var WebsiteGeneralSettings $generalSettings */
        $generalSettings = app(WebsiteGeneralSettings::class);

        $title = $generalSettings->getTranslatable('site_title');

        if (Route::currentRouteName() === 'login') {
            $title = __('Login');
        } elseif (Route::currentRouteName() === 'register') {
            $title = __('Register');
        }

        // Ensure separator has spaces on both sides
        $separator = $generalSettings->title_separator;
        if (!Str::startsWith($separator, ' ')) {
            $separator = ' ' . $separator;
        }
        if (!Str::endsWith($separator, ' ')) {
            $separator = $separator . ' ';
        }

        $defaultTitle = sprintf('%s%s%s', $title, $separator, $generalSettings->getTranslatable('tagline'));

        $seoMeta = SEOMeta::setTitleDefault($defaultTitle)->generate();

        return Str::replace('<title>', '<title inertia>', $seoMeta);
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * @param $currencyCode
     * @return string
     */
    function get_currency_symbol($currencyCode): string
    {
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

        // Set the currency code to get the correct symbol
        $numberFormatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currencyCode);

        return $numberFormatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    if (!function_exists('current_theme_name')) {
        function current_theme_name(): string
        {
            return app()->make(AppearanceThemeSettings::class)->current_theme_name ?? 'Breeze'; // Adjust based on how you store settings
        }
    }

    if (!function_exists('is_current_route')) {
        /**
         * @param string $routeName
         * @return bool
         */
        function is_current_route(string $routeName)
        {
            return Route::currentRouteName() === $routeName;
        }
    }
}
