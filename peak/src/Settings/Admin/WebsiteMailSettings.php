<?php

namespace Qoraiche\Peak\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class WebsiteMailSettings extends Settings
{
    public string $mailer;

    // sendmail path
    public ?string $sendmail_path;

    public ?string $host;
    public ?string $port;
    public ?string $username;
    public ?string $password;
    public ?string $encryption;
    public ?string $from_address;
    public ?string $from_name;

    // mailgun
    public ?string $mailgun_domain;
    public ?string $mailgun_secret;
    public ?string $mailgun_endpoint;
    public ?string $postmark_token;
    public ?string $resend_key;
    public ?string $ses_key;
    public ?string $ses_secret;
    public ?string $ses_region;
    public ?string $mailersend_api_key;

    public static function group(): string
    {
        return 'website-mail';
    }
}
