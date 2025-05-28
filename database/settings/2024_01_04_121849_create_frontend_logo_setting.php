<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-logo.main_logo_path', null);
        $this->migrator->add('frontend-logo.favicon_path', null);
    }
};
