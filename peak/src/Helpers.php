<?php

namespace Qoraiche\Peak;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Qoraiche\Peak\Models\Language;
use Spatie\TranslationLoader\LanguageLine;

class Helpers
{
    /**
     * @return Repository|Application|mixed|object|null
     */
    public static function getUserAuthModelName()
    {
        return config('auth.providers.users.model');
    }

    public static function getUserAuthModelInstance()
    {
        return app(config('auth.providers.users.model'));
    }

    /**
     * @param $locale
     * @return array|string
     */
    public static function currentLocaleTranslations($locale = null): array|string
    {
        $locale = $locale ?? self::getLocale(); // Get the current locale

        App::setLocale($locale);

        // Load JSON translations from file

        return Lang::get('*');
    }

    /**
     * @return Application|SessionManager|mixed|object|string
     */
    public static function getLocale()
    {
        return session('locale') ?? (auth()->check() ? auth()->user()->preferredLocale() : app()->getLocale());
    }
}
