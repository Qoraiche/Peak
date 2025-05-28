<?php

namespace Qoraiche\Peak\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class WebsiteAuthProvidersSettings extends Settings
{
    public bool $facebook_active;
    public ?string $facebook_client_id;
    public ?string $facebook_client_secret;
    // x
    public bool $x_active;
    public ?string $x_client_id;
    public ?string $x_client_secret;
    // linkedin
    public bool $linkedin_active;
    public ?string $linkedin_client_id;
    public ?string $linkedin_client_secret;
    // google
    public bool $google_active;
    public ?string $google_client_id;
    public ?string $google_client_secret;
    // github
    public bool $github_active;
    public ?string $github_client_id;
    public ?string $github_client_secret;
    // gitlab
    public bool $gitlab_active;
    public ?string $gitlab_client_id;
    public ?string $gitlab_client_secret;
    // bitbucket
    public bool $bitbucket_active;
    public ?string $bitbucket_client_id;
    public ?string $bitbucket_client_secret;
    // slack
    public bool $slack_active;
    public ?string $slack_client_id;
    public ?string $slack_client_secret;

    public static function group(): string
    {
        return 'website-auth-providers';
    }
}
