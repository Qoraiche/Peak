<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('selling-business-information.company_name', 'Your company');
        $this->migrator->add('selling-business-information.street','111 Example St.');
        $this->migrator->add('selling-business-information.location', 'Rabat, MA');
        $this->migrator->add('selling-business-information.phone', '555-555-5555');
        $this->migrator->add('selling-business-information.email', 'contact@example.com');
    }
};
