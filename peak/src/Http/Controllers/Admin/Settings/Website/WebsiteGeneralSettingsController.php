<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Settings\Website;

use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Requests\Admin\Settings\Website\UpdateWebsiteGeneralSettingsRequest;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;

class WebsiteGeneralSettingsController extends Controller
{
    /**
     * @param WebsiteGeneralSettings $websiteGeneralSettings
     */
    public function __construct(private WebsiteGeneralSettings $websiteGeneralSettings)
    {
    }

    /**
     * Settings
     *
     * @return Response
     */
    public function settings(): Response
    {
        return Inertia::render('Admin/Settings/Website/General', [
            'timezones' => global_timezones(),
            'currencies' => global_currencies(),
            'settings' => [
                'site_title' => $this->websiteGeneralSettings->getTranslatable('site_title'),
                'tagline' => $this->websiteGeneralSettings->getTranslatable('tagline'),
                'title_separator' => $this->websiteGeneralSettings->title_separator,
                'currency_code' => $this->websiteGeneralSettings->currency,
                'default_language' => $this->websiteGeneralSettings->default_language,
                'timezone' => $this->websiteGeneralSettings->timezone,
                'date_format' => $this->websiteGeneralSettings->getTranslatable('date_format'),
                'date_time_format' => $this->websiteGeneralSettings->getTranslatable('date_time_format'),
                'company_name' => $this->websiteGeneralSettings->company_name,
                'company_address' => $this->websiteGeneralSettings->company_address,
                'company_phone' => $this->websiteGeneralSettings->company_phone,
                'contact_email' => $this->websiteGeneralSettings->email_address,
                'tos_url' => $this->websiteGeneralSettings->tos_url,
                'privacy_policy_url' => $this->websiteGeneralSettings->privacy_policy_url,
                'tiny_mce_api_key' => $this->websiteGeneralSettings->tiny_mce_api_key,
                'seo_description' => $this->websiteGeneralSettings->getTranslatable('seo_description'),
                'seo_keywords' => $this->websiteGeneralSettings->getTranslatable('seo_keywords')
            ],
        ]);
    }

    /**
     * @param UpdateWebsiteGeneralSettingsRequest $updateWebsiteGeneralSettingsRequest
     * @return void
     */
    public function update(UpdateWebsiteGeneralSettingsRequest $updateWebsiteGeneralSettingsRequest): void
    {
        $currency = $updateWebsiteGeneralSettingsRequest->input('currency_code');

        $this->websiteGeneralSettings->setTranslatable('site_title', $updateWebsiteGeneralSettingsRequest->input('site_title'));
        $this->websiteGeneralSettings->setTranslatable('tagline', $updateWebsiteGeneralSettingsRequest->input('tagline'));
        $this->websiteGeneralSettings->setTranslatable('date_format', $updateWebsiteGeneralSettingsRequest->input('date_format'));
        $this->websiteGeneralSettings->setTranslatable('date_time_format', $updateWebsiteGeneralSettingsRequest->input('date_time_format'));
        $this->websiteGeneralSettings->setTranslatable('seo_description', $updateWebsiteGeneralSettingsRequest->input('seo_description'));
        $this->websiteGeneralSettings->setTranslatable('seo_keywords', $updateWebsiteGeneralSettingsRequest->input('seo_keywords'));
        $this->websiteGeneralSettings->title_separator = $updateWebsiteGeneralSettingsRequest->input('title_separator');
        $this->websiteGeneralSettings->currency = $currency;
        $this->websiteGeneralSettings->default_language = $updateWebsiteGeneralSettingsRequest->input('default_language');
        $this->websiteGeneralSettings->timezone = $updateWebsiteGeneralSettingsRequest->input('timezone');
        $this->websiteGeneralSettings->company_name = $updateWebsiteGeneralSettingsRequest->input('company_name');
        $this->websiteGeneralSettings->company_address = $updateWebsiteGeneralSettingsRequest->input('company_address');
        $this->websiteGeneralSettings->company_phone = $updateWebsiteGeneralSettingsRequest->input('company_phone');
        $this->websiteGeneralSettings->email_address = $updateWebsiteGeneralSettingsRequest->input('contact_email');
        $this->websiteGeneralSettings->tos_url = $updateWebsiteGeneralSettingsRequest->input('tos_url');
        $this->websiteGeneralSettings->privacy_policy_url = $updateWebsiteGeneralSettingsRequest->input('privacy_policy_url');
        $this->websiteGeneralSettings->tiny_mce_api_key = $updateWebsiteGeneralSettingsRequest->input('tiny_mce_api_key');

        $this->websiteGeneralSettings->save();

        set_app_environment_value([
            'CASHIER_CURRENCY' => $currency,
            'APP_TIMEZONE' => $updateWebsiteGeneralSettingsRequest->timezone,
            'DEFAULT_SITE_DESCRIPTION' => $updateWebsiteGeneralSettingsRequest->tagline,
            'DEFAULT_SITE_KEYWORDS' => $updateWebsiteGeneralSettingsRequest->seo_keywords,
            'DEFAULT_SITE_TITLE_SEPARATOR' => '"' . $updateWebsiteGeneralSettingsRequest->title_separator . '"'
        ]);
    }
}
