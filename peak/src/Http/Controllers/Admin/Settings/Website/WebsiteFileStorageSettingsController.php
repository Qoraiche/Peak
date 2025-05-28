<?php

namespace Qoraiche\Peak\Http\Controllers\Admin\Settings\Website;

use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Http\Requests\Admin\Settings\Website\UpdateWebsiteFileStorageSettingsRequest;
use Qoraiche\Peak\Settings\Admin\WebsiteFileStorageSettings;

class WebsiteFileStorageSettingsController extends Controller
{
    /**
     * Settings
     * @return Response
     */
    public function settings(WebsiteFileStorageSettings $websiteFileStorageSettings): Response
    {
        return Inertia::render('Admin/Settings/Website/FileStorage', [
            'disk' => $websiteFileStorageSettings->disk,
            's3_key' => $websiteFileStorageSettings->s3_key,
            's3_secret' => $websiteFileStorageSettings->s3_secret,
            's3_region' => $websiteFileStorageSettings->s3_region,
            's3_bucket' => $websiteFileStorageSettings->s3_bucket,
            's3_url' => $websiteFileStorageSettings->s3_url,
            's3_endpoint' => $websiteFileStorageSettings->s3_endpoint
        ]);
    }

    /**
     * @param UpdateWebsiteFileStorageSettingsRequest $updateWebsiteFileStorageSettingsRequest
     * @param WebsiteFileStorageSettings $websiteFileStorageSettings
     * @return void
     */
    public function update(UpdateWebsiteFileStorageSettingsRequest $updateWebsiteFileStorageSettingsRequest, WebsiteFileStorageSettings $websiteFileStorageSettings): void
    {
        $websiteFileStorageSettings->disk = $updateWebsiteFileStorageSettingsRequest->input('disk');

        if ($updateWebsiteFileStorageSettingsRequest->input('disk') === 's3') {
            $websiteFileStorageSettings->s3_key = $updateWebsiteFileStorageSettingsRequest->input('s3_key');
            $websiteFileStorageSettings->s3_secret = $updateWebsiteFileStorageSettingsRequest->input('s3_secret');
            $websiteFileStorageSettings->s3_region = $updateWebsiteFileStorageSettingsRequest->input('s3_region');
            $websiteFileStorageSettings->s3_bucket = $updateWebsiteFileStorageSettingsRequest->input('s3_bucket');
            $websiteFileStorageSettings->s3_url = $updateWebsiteFileStorageSettingsRequest->input('s3_url');
            $websiteFileStorageSettings->s3_endpoint = $updateWebsiteFileStorageSettingsRequest->input('s3_endpoint');

            set_app_environment_value([
                'AWS_ACCESS_KEY_ID' => $updateWebsiteFileStorageSettingsRequest->input('s3_key'),
                'AWS_SECRET_ACCESS_KEY' => $updateWebsiteFileStorageSettingsRequest->input('s3_secret'),
                'AWS_DEFAULT_REGION' => $updateWebsiteFileStorageSettingsRequest->input('s3_region'),
                'AWS_BUCKET' => $updateWebsiteFileStorageSettingsRequest->input('s3_bucket'),
                'AWS_URL' => $updateWebsiteFileStorageSettingsRequest->input('s3_url'),
                'AWS_ENDPOINT' => $updateWebsiteFileStorageSettingsRequest->input('s3_endpoint')
            ]);
        }

        $websiteFileStorageSettings->save();

        set_app_environment_value([
            'MEDIA_DISK' => $updateWebsiteFileStorageSettingsRequest->input('disk'),
            'FILESYSTEM_DISK' => $updateWebsiteFileStorageSettingsRequest->input('disk'),
        ]);
    }
}
