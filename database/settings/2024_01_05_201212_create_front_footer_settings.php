<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-footer.footer_copyright_text', ['en' => '© 2025 Your Company, Inc. All rights reserved.']);
    }
};
