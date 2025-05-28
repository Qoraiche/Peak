<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-mail.mailer', 'smtp');
        $this->migrator->add('website-mail.sendmail_path', '/usr/sbin/sendmail -bs -i');
        $this->migrator->add('website-mail.host', '127.0.0.1');
        $this->migrator->add('website-mail.port', '2525');
        $this->migrator->add('website-mail.username', null);
        $this->migrator->add('website-mail.password', null);
        $this->migrator->add('website-mail.encryption', 'tls');
        $this->migrator->add('website-mail.from_address', 'contact@example.com');
        $this->migrator->add('website-mail.from_name', 'Peak');
        //
        $this->migrator->add('website-mail.mailgun_domain', null);
        $this->migrator->add('website-mail.mailgun_secret', null);
        $this->migrator->add('website-mail.mailgun_endpoint', null);
        //

        $this->migrator->add('website-mail.postmark_token', null);
        $this->migrator->add('website-mail.resend_key', null);
        $this->migrator->add('website-mail.ses_key', null);
        $this->migrator->add('website-mail.ses_secret', null);
        $this->migrator->add('website-mail.ses_region', null);
        $this->migrator->add('website-mail.mailersend_api_key', null);
    }
};
