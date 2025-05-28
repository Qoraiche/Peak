<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('selling.proration_enabled', true);
        $this->migrator->add('selling.promotion_codes_enabled', true);
        $this->migrator->add('selling.sends_invoices_to_custom_addresses', true);
        $this->migrator->add('selling.collect_vat_enabled', false);
        $this->migrator->add('selling.automatically_calculate_taxes', false);
        $this->migrator->add('selling.cancel_subscription_immediately', true);
        $this->migrator->add('selling.billing_address_enabled', true);
        $this->migrator->add('selling.skip_trial_if_subscribed_before_enabled', true);
        $this->migrator->add('selling.generic_trial_without_payment_method_enabled', true);
        $this->migrator->add('selling.limit_user_trials_enabled', true);
        $this->migrator->add('selling.first_reminder_enabled', true);
        $this->migrator->add('selling.second_reminder_enabled', true);
        $this->migrator->add('selling.maximum_trial_count', 1);
        $this->migrator->add('selling.trial_end_first_reminder_days', 3);
        $this->migrator->add('selling.trial_end_second_reminder_days', 1);
    }
};
