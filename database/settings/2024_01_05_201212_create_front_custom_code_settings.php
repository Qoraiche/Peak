<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-custom-code.custom_css');
        $this->migrator->add('frontend-custom-code.custom_js');
    }
};
