<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-header.register_button_text', ['en' => 'Register']);
        $this->migrator->add('frontend-header.login_button_text', ['en' => 'Login']);
    }
};
