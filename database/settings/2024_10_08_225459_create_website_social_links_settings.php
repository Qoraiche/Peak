<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('website-social-links.facebook_link', null);
        $this->migrator->add('website-social-links.twitter_x_link', null);
        $this->migrator->add('website-social-links.instagram_link', null);
        $this->migrator->add('website-social-links.youtube_link', null);
        $this->migrator->add('website-social-links.twitch_link', null);
        $this->migrator->add('website-social-links.tiktok_link', null);
        $this->migrator->add('website-social-links.linkedin_link', null);
        $this->migrator->add('website-social-links.snapchat_link', null);
        $this->migrator->add('website-social-links.reddit_link', null);
        $this->migrator->add('website-social-links.pinterest_link', null);
        $this->migrator->add('website-social-links.discord_link', null);
        $this->migrator->add('website-social-links.whatsapp_link', null);
        $this->migrator->add('website-social-links.telegram_link', null);
        $this->migrator->add('website-social-links.github_link', null);
        $this->migrator->add('website-social-links.spotify_link', null);
        $this->migrator->add('website-social-links.soundcloud_link', null);
    }
};
