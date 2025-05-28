<?php

use Qoraiche\Peak\Settings\Admin\AppearanceThemeSettings;
use Qoraiche\Peak\Settings\Admin\FrontCustomCodeSettings;
use Qoraiche\Peak\Settings\Admin\FrontendAppearanceSettings;
use Qoraiche\Peak\Settings\Admin\FrontendHeroSettings;
use Qoraiche\Peak\Settings\Admin\FrontendLogoSettings;
use Qoraiche\Peak\Settings\Admin\FrontFooterSettings;
use Qoraiche\Peak\Settings\Admin\FrontHeaderSettings;
use Qoraiche\Peak\Settings\Admin\MarketingAnnouncementSettings;
use Qoraiche\Peak\Settings\Admin\MarketingNewsLetterSettings;
use Qoraiche\Peak\Settings\Admin\MarketingNotificationsSettings;
use Qoraiche\Peak\Settings\Admin\MarketingTransactionalEmailsSettings;
use Qoraiche\Peak\Settings\Admin\SellingBusinessInformationSettings;
use Qoraiche\Peak\Settings\Admin\SellingPaddlePaymentSettings;
use Qoraiche\Peak\Settings\Admin\SellingSettings;
use Qoraiche\Peak\Settings\Admin\SellingStripePaymentSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteAnalyticsSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteAuthProvidersSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteCookieSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteFileStorageSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteHomePageSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteMailSettings;
use Qoraiche\Peak\Settings\Admin\WebsitePwaSettings;
use Qoraiche\Peak\Settings\Admin\WebsiteSecuritySettings;
use Qoraiche\Peak\Settings\Admin\WebsiteSiteAvailabilitySettings;
use Qoraiche\Peak\Settings\Admin\WebsiteSocialLinksSettings;
use Qoraiche\Peak\Settings\FrontHeadingsSettings;

return [

    /*
     * Each settings class used in your application must be registered, you can
     * put them (manually) here.
     */
    'settings' => [
        AppearanceThemeSettings::class,
        FrontCustomCodeSettings::class,
        FrontendAppearanceSettings::class,
        FrontendHeroSettings::class,
        FrontendLogoSettings::class,
        FrontFooterSettings::class,
        FrontHeaderSettings::class,
        MarketingAnnouncementSettings::class,
        MarketingNewsLetterSettings::class,
        MarketingNotificationsSettings::class,
        MarketingTransactionalEmailsSettings::class,
        SellingBusinessInformationSettings::class,
        SellingPaddlePaymentSettings::class,
        SellingSettings::class,
        SellingStripePaymentSettings::class,
        WebsiteAnalyticsSettings::class,
        WebsiteAuthProvidersSettings::class,
        WebsiteCookieSettings::class,
        WebsiteFileStorageSettings::class,
        WebsiteHomePageSettings::class,
        WebsiteMailSettings::class,
        WebsitePwaSettings::class,
        WebsiteSecuritySettings::class,
        WebsiteSiteAvailabilitySettings::class,
        WebsiteSocialLinksSettings::class,
        FrontHeadingsSettings::class,
        WebsiteGeneralSettings::class,
        WebsiteFileStorageSettings::class,
    ],

    /*
     * The path where the settings classes will be created.
     */
    'setting_class_path' => app_path('Settings'),

    /*
     * In these directories settings migrations will be stored and ran when migrating. A settings
     * migration created via the make:settings-migration command will be stored in the first path or
     * a custom defined path when running the command.
     */
    'migrations_paths' => [
        database_path('settings'),
    ],

    /*
     * When no repository was set for a settings class the following repository
     * will be used for loading and saving settings.
     */
    'default_repository' => 'database',

    /*
     * Settings will be stored and loaded from these repositories.
     */
    'repositories' => [
        'database' => [
            'type' => Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository::class,
            'model' => null,
            'table' => null,
            'connection' => null,
        ],
        'redis' => [
            'type' => Spatie\LaravelSettings\SettingsRepositories\RedisSettingsRepository::class,
            'connection' => null,
            'prefix' => null,
        ],
    ],

    /*
     * The encoder and decoder will determine how settings are stored and
     * retrieved in the database. By default, `json_encode` and `json_decode`
     * are used.
     */
    'encoder' => null,
    'decoder' => null,

    /*
     * The contents of settings classes can be cached through your application,
     * settings will be stored within a provided Laravel store and can have an
     * additional prefix.
     */
    'cache' => [
        'enabled' => env('SETTINGS_CACHE_ENABLED', false),
        'store' => null,
        'prefix' => null,
        'ttl' => null,
    ],

    /*
     * These global casts will be automatically used whenever a property within
     * your settings class isn't a default PHP type.
     */
    'global_casts' => [
        DateTimeInterface::class => Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast::class,
        DateTimeZone::class => Spatie\LaravelSettings\SettingsCasts\DateTimeZoneCast::class,
//        Spatie\DataTransferObject\DataTransferObject::class => Spatie\LaravelSettings\SettingsCasts\DtoCast::class,
        Spatie\LaravelData\Data::class => Spatie\LaravelSettings\SettingsCasts\DataCast::class,
    ],

    /*
     * The package will look for settings in these paths and automatically
     * register them.
     */
    'auto_discover_settings' => [
        app_path('Settings'),
    ],

    /*
     * Automatically discovered settings classes can be cached, so they don't
     * need to be searched each time the application boots up.
     */
    'discovered_settings_cache_path' => base_path('bootstrap/cache'),
];
