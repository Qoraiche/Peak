<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('marketing-announcement.announcement_enabled', false);
        $this->migrator->add('marketing-announcement.announcement_text', []);
        $this->migrator->add('marketing-announcement.announcement_cta_text', []);
        $this->migrator->add('marketing-announcement.announcement_cta_link', []);
    }
};
