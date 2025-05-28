<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('selling-stripe-payment.label', []);
        $this->migrator->add('selling-stripe-payment.description', []);
        $this->migrator->add('selling-stripe-payment.active', false);
        $this->migrator->add('selling-stripe-payment.key', null);
        $this->migrator->add('selling-stripe-payment.secret', null);
        $this->migrator->add('selling-stripe-payment.webhook_secret', null);
    }
};
