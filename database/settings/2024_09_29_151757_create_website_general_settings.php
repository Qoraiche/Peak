<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-general.site_title', ['en' => 'Peak']);
        $this->migrator->add('website-general.tagline', ['en' => '']);
        $this->migrator->add('website-general.title_separator', '-');
        $this->migrator->add('website-general.timezone', 'UTC');
        $this->migrator->add('website-general.date_format', ['en' => 'M d, Y']);
        $this->migrator->add('website-general.date_time_format', ['en' => 'M d, Y h:i A']);
        $this->migrator->add('website-general.default_language', 'en');
        $this->migrator->add('website-general.currency', 'USD');
        $this->migrator->add('website-general.company_name', '');
        $this->migrator->add('website-general.company_address', '');
        $this->migrator->add('website-general.company_phone', '+1 (555) 905-2345');
        $this->migrator->add('website-general.email_address', 'contact@example.com');
        $this->migrator->add('website-general.tos_url', '');
        $this->migrator->add('website-general.privacy_policy_url', '');
        $this->migrator->add('website-general.tiny_mce_api_key', '');
        $this->migrator->add('website-general.seo_description', []);
        $this->migrator->add('website-general.seo_keywords', []);
    }
};
