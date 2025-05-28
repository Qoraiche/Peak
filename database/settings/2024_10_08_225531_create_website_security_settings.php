<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-security.force_https_enabled', true);
        $this->migrator->add('website-security.email_verification_enabled', true);
        $this->migrator->add('website-security.login_enabled', true);
        $this->migrator->add('website-security.login_with_username', false);
        $this->migrator->add('website-security.reset_password_enabled', true);
        $this->migrator->add('website-security.registration_enabled', true);
        $this->migrator->add('website-security.two_factor_auth_enabled', true);
        $this->migrator->add('website-security.password_reset_expire', 60);
        $this->migrator->add('website-security.password_reset_throttle', 60);
        $this->migrator->add('website-security.session_lifetime', 9999);
        $this->migrator->add('website-security.recaptcha_enabled', false);
        $this->migrator->add('website-security.magic_login_enabled', true);
        $this->migrator->add('website-security.social_login_enabled', true);
        $this->migrator->add('website-security.profile_management_enabled', true);
        $this->migrator->add('website-security.password_update_enabled', true);
        $this->migrator->add('website-security.teams_management_enabled', true);
        $this->migrator->add('website-security.account_deletion_enabled', true);
        $this->migrator->add('website-security.profile_browser_sessions_enabled', true);
        $this->migrator->add('website-security.recaptcha_secret', null);
        $this->migrator->add('website-security.recaptcha_sitekey', null);
        $this->migrator->add('website-security.recaptcha_theme', 'light');
    }
};
