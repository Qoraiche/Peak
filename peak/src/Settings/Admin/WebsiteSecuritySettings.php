<?php

namespace Qoraiche\Peak\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class WebsiteSecuritySettings extends Settings
{
    public bool $force_https_enabled;
    public bool $email_verification_enabled;
    public bool $login_enabled;
    public bool $reset_password_enabled;
    public bool $login_with_username;
    public bool $registration_enabled;
    public bool $two_factor_auth_enabled;
    public string|int $password_reset_expire;
    public string|int $session_lifetime;
    public string|int $password_reset_throttle;
    public bool $recaptcha_enabled;
    public ?string $recaptcha_secret;
    public ?string $recaptcha_sitekey;
    public ?string $recaptcha_theme;
    public bool $magic_login_enabled;
    public bool $profile_management_enabled;
    public bool $password_update_enabled;
    public bool $teams_management_enabled;
    public bool $account_deletion_enabled;
    public bool $profile_browser_sessions_enabled;
    public bool $social_login_enabled;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'website-security';
    }
}
