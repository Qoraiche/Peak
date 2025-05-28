<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Settings\Website;

use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Requests\Admin\Settings\Website\UpdateWebsiteMailSettingsRequest;
use Qoraiche\Peak\Settings\Admin\WebsiteMailSettings;

class WebsiteMailSettingsController extends Controller
{
    /**
     * Settings
     * @param WebsiteMailSettings $websiteMailSettings
     * @return Response
     */
    public function settings(WebsiteMailSettings $websiteMailSettings): Response
    {
        return Inertia::render('Admin/Settings/Website/Mail', [
            'mailer' => $websiteMailSettings->mailer,
            'sendmail_path' => $websiteMailSettings->sendmail_path,
            'host' => $websiteMailSettings->host,
            'port' => $websiteMailSettings->port,
            'username' => $websiteMailSettings->username,
            'password' => $websiteMailSettings->password,
            'encryption' => $websiteMailSettings->encryption,
            'from_name' => $websiteMailSettings->from_name,
            'mailgun_domain' => $websiteMailSettings->mailgun_domain,
            'mailgun_secret' => $websiteMailSettings->mailgun_secret,
            'mailgun_endpoint' => $websiteMailSettings->mailgun_endpoint,
            'postmark_token' => $websiteMailSettings->postmark_token,
            'resend_key' => $websiteMailSettings->resend_key,
            'ses_key' => $websiteMailSettings->ses_key,
            'ses_secret' => $websiteMailSettings->ses_secret,
            'ses_region' => $websiteMailSettings->ses_region,
            'mailersend_api_key' => $websiteMailSettings->mailersend_api_key,
        ]);
    }

    /**
     * @param UpdateWebsiteMailSettingsRequest $updateWebsiteMailSettingsRequest
     * @param WebsiteMailSettings $websiteMailSettings
     * @return void
     */
    public function update(UpdateWebsiteMailSettingsRequest $updateWebsiteMailSettingsRequest, WebsiteMailSettings $websiteMailSettings): void
    {
        $websiteMailSettings->mailer = $updateWebsiteMailSettingsRequest->input('mailer');

        $fromName = $updateWebsiteMailSettingsRequest->input('from_name');
        $fromEmail = $updateWebsiteMailSettingsRequest->input('from_address');
        $mailer = $updateWebsiteMailSettingsRequest->input('mailer');

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'smtp') {
            $websiteMailSettings->host = $updateWebsiteMailSettingsRequest->input('host');
            $websiteMailSettings->port = $updateWebsiteMailSettingsRequest->input('port');
            $websiteMailSettings->username = $updateWebsiteMailSettingsRequest->input('username');
            $websiteMailSettings->password = $updateWebsiteMailSettingsRequest->input('password');
            $websiteMailSettings->encryption = $updateWebsiteMailSettingsRequest->input('encryption');
            $websiteMailSettings->from_name = $updateWebsiteMailSettingsRequest->input('from_name');
            $websiteMailSettings->from_address = $updateWebsiteMailSettingsRequest->input('from_address');

            $websiteMailSettings->save();

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'MAIL_HOST' => $updateWebsiteMailSettingsRequest->input('host'),
                'MAIL_PORT' => $updateWebsiteMailSettingsRequest->input('port'),
                'MAIL_USERNAME' => $updateWebsiteMailSettingsRequest->input('username'),
                'MAIL_PASSWORD' => $updateWebsiteMailSettingsRequest->input('password'),
                'MAIL_ENCRYPTION' => $updateWebsiteMailSettingsRequest->input('encryption'),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'sendmail') {
            $websiteMailSettings->sendmail_path = $updateWebsiteMailSettingsRequest->input('sendmail_path');

            $websiteMailSettings->save();

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'MAIL_SENDMAIL_PATH' => sprintf('"%s"', $updateWebsiteMailSettingsRequest->input('sendmail_path')),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'mailgun') {
            $websiteMailSettings->mailgun_domain = $updateWebsiteMailSettingsRequest->input('mailgun_domain');
            $websiteMailSettings->mailgun_secret = $updateWebsiteMailSettingsRequest->input('mailgun_secret');
            $websiteMailSettings->mailgun_endpoint = $updateWebsiteMailSettingsRequest->input('mailgun_endpoint');

            $websiteMailSettings->save();

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'MAILGUN_DOMAIN' => $updateWebsiteMailSettingsRequest->input('mailgun_domain'),
                'MAILGUN_SECRET' => $updateWebsiteMailSettingsRequest->input('mailgun_secret'),
                'MAILGUN_ENDPOINT' => $updateWebsiteMailSettingsRequest->input('mailgun_endpoint'),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'postmark') {
            $websiteMailSettings->postmark_token = $updateWebsiteMailSettingsRequest->input('postmark_token');

            $websiteMailSettings->save();

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'POSTMARK_TOKEN' => $updateWebsiteMailSettingsRequest->input('postmark_token'),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'resend') {
            $websiteMailSettings->resend_key = $updateWebsiteMailSettingsRequest->input('resend_key');

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'RESEND_KEY' => $updateWebsiteMailSettingsRequest->input('resend_key'),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'mailersend') {
            $websiteMailSettings->mailersend_api_key = $updateWebsiteMailSettingsRequest->input('mailersend_api_key');

            $websiteMailSettings->save();

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'MAILERSEND_API_KEY' => $updateWebsiteMailSettingsRequest->input('mailersend_api_key'),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }

        if ($updateWebsiteMailSettingsRequest->input('mailer') === 'ses') {
            $websiteMailSettings->ses_key = $updateWebsiteMailSettingsRequest->input('ses_key');
            $websiteMailSettings->ses_secret = $updateWebsiteMailSettingsRequest->input('ses_secret');
            $websiteMailSettings->ses_region = $updateWebsiteMailSettingsRequest->input('ses_region');

            $websiteMailSettings->save();

            set_app_environment_value([
                'MAIL_MAILER' => $mailer,
                'AWS_ACCESS_KEY_ID' => $updateWebsiteMailSettingsRequest->input('ses_key'),
                'AWS_SECRET_ACCESS_KEY' => $updateWebsiteMailSettingsRequest->input('ses_secret'),
                'AWS_DEFAULT_REGION' => $updateWebsiteMailSettingsRequest->input('ses_region'),
                'MAIL_FROM_ADDRESS' => sprintf('"%s"', $fromEmail),
                'MAIL_FROM_NAME' => sprintf('"%s"', $fromName)
            ]);
        }
    }
}
