<?php

namespace Qoraiche\Peak\Settings\Admin;

use Qoraiche\Peak\Services\TranslatableBaseSettings;

class WebsiteGeneralSettings extends TranslatableBaseSettings
{
    public ?array $site_title;
    public ?array $tagline;
    public ?string $title_separator;
    public string $default_language;
    public ?array $date_format;
    public ?array $date_time_format;
    public string $timezone;
    public string $currency;
    public ?string $company_name;
    public ?string $company_address;
    public ?string $company_phone;
    public ?string $email_address;
    public ?string $tos_url;
    public ?string $privacy_policy_url;
    public ?string $tiny_mce_api_key;
    public ?array $seo_description;
    public ?array $seo_keywords;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'website-general';
    }
}
