<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('marketing-transactional-emails.send_welcome_email', true);
        $this->migrator->add('marketing-transactional-emails.welcome_email_content', null);
        $this->migrator->add('marketing-transactional-emails.send_channel_goes_live_email', true);
        $this->migrator->add('marketing-transactional-emails.channel_goes_live_email_content', null);
    }
};
