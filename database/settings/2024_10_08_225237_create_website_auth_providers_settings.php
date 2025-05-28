<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-auth-providers.facebook_active', false);
        $this->migrator->add('website-auth-providers.facebook_client_id', null);
        $this->migrator->add('website-auth-providers.facebook_client_secret', null);
        $this->migrator->add('website-auth-providers.x_active', false);
        $this->migrator->add('website-auth-providers.x_client_id', null);
        $this->migrator->add('website-auth-providers.x_client_secret', null);
        $this->migrator->add('website-auth-providers.linkedin_active', false);
        $this->migrator->add('website-auth-providers.linkedin_client_id', null);
        $this->migrator->add('website-auth-providers.linkedin_client_secret', null);
        $this->migrator->add('website-auth-providers.google_active', false);
        $this->migrator->add('website-auth-providers.google_client_id', null);
        $this->migrator->add('website-auth-providers.google_client_secret', null);
        $this->migrator->add('website-auth-providers.github_active', false);
        $this->migrator->add('website-auth-providers.github_client_id', null);
        $this->migrator->add('website-auth-providers.github_client_secret', null);
        $this->migrator->add('website-auth-providers.gitlab_active', false);
        $this->migrator->add('website-auth-providers.gitlab_client_id', null);
        $this->migrator->add('website-auth-providers.gitlab_client_secret', null);
        $this->migrator->add('website-auth-providers.bitbucket_active', false);
        $this->migrator->add('website-auth-providers.bitbucket_client_id', null);
        $this->migrator->add('website-auth-providers.bitbucket_client_secret', null);
        $this->migrator->add('website-auth-providers.slack_active', false);
        $this->migrator->add('website-auth-providers.slack_client_id', null);
        $this->migrator->add('website-auth-providers.slack_client_secret', null);
    }
};
