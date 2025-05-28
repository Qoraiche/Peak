<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-appearance.text_color', '#111827');
        $this->migrator->add('frontend-appearance.cta_button_text_color', '#ffffff');
        $this->migrator->add('frontend-appearance.cta_button_background_color', '#111827');
        $this->migrator->add('frontend-appearance.background_color', 'bg-gradient-to-r from-pink-300 via-purple-300 to-indigo-400');
    }
};
