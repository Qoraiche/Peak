<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('marketing-notifications.notify_user_registration', true);
        $this->migrator->add('marketing-notifications.notify_user_login', false);
        $this->migrator->add('marketing-notifications.notify_user_reset_password', false);
        $this->migrator->add('marketing-notifications.notify_user_app_subscription', true);
        $this->migrator->add('marketing-notifications.notify_user_purchase_channel_subscription', true);
        $this->migrator->add('marketing-notifications.notify_user_purchase_channel_gifts', true);
        $this->migrator->add('marketing-notifications.notify_user_channel_goes_live', true);
    }
};
