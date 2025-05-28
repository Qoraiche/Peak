<?php

namespace Qoraiche\Peak\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class FrontCustomCodeSettings extends Settings
{
    public ?string $custom_css;
    public ?string $custom_js;

    public static function group(): string
    {
        return 'frontend-custom-code';
    }
}
