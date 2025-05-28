<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-site-availability.mode', 'public');
        $this->migrator->add('website-site-availability.content', []);
    }
};
