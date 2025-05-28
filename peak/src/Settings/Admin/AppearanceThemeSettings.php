<?php

namespace Qoraiche\Peak\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class AppearanceThemeSettings extends Settings
{
    /** @var string */
    public string $current_theme_name;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'appearance-theme';
    }
}
