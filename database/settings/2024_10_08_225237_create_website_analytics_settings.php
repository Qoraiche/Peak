<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-analytics.google_analytics_property_id', null);
        $this->migrator->add('website-analytics.google_service_account_credentials_json', null);
    }
};
