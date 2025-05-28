<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('marketing-newsletter.mailchimp_api_key', null);
        $this->migrator->add('marketing-newsletter.mailchimp_list_subscribers_id', null);
        $this->migrator->add('marketing-newsletter.mailchimp_default_list_name', 'subscribers');
    }
};
