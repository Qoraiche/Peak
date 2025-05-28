<?php

namespace Qoraiche\Peak\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class WebsiteFileStorageSettings extends Settings
{
    public string $disk; // local, public, s3
    // s3
    public ?string $s3_key;
    public ?string $s3_secret;
    public ?string $s3_region;
    public ?string $s3_bucket;
    public ?string $s3_url;
    public ?string $s3_endpoint;

    public static function group(): string
    {
        return 'website-file-storage';
    }
}
