<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('selling-paddle-payment.label', []);
        $this->migrator->add('selling-paddle-payment.description', []);
        $this->migrator->add('selling-paddle-payment.active', false);
        $this->migrator->add('selling-paddle-payment.sandbox', true);
        $this->migrator->add('selling-paddle-payment.client_side_token', null);
        $this->migrator->add('selling-paddle-payment.api_key', null);
        $this->migrator->add('selling-paddle-payment.retain_key', null);
        $this->migrator->add('selling-paddle-payment.webhook_secret', null);
    }
};
