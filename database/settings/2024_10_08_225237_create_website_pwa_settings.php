<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        // Mobile App
        $this->migrator->add('website-pwa.pwa_enabled', true);
        $this->migrator->add('website-pwa.name', 'My PWA App');
        $this->migrator->add('website-pwa.short_name', 'PWA');
        $this->migrator->add('website-pwa.start_url', '/');
        $this->migrator->add('website-pwa.background_color', '#ffffff');
        $this->migrator->add('website-pwa.theme_color', '#000000');
        $this->migrator->add('website-pwa.display', 'standalone');
        $this->migrator->add('website-pwa.orientation', 'any');
        $this->migrator->add('website-pwa.status_bar', '#000000');

        // icons
        $this->migrator->add('website-pwa.icons_72x72', '/images/icons/icon-72x72.png');
        $this->migrator->add('website-pwa.icons_96x96', '/images/icons/icon-96x96.png');
        $this->migrator->add('website-pwa.icons_128x128', '/images/icons/icon-128x128.png');
        $this->migrator->add('website-pwa.icons_144x144', '/images/icons/icon-144x144.png');
        $this->migrator->add('website-pwa.icons_152x152', '/images/icons/icon-152x152.png');
        $this->migrator->add('website-pwa.icons_192x192', '/images/icons/icon-192x192.png');
        $this->migrator->add('website-pwa.icons_384x384', '/images/icons/icon-384x384.png');
        $this->migrator->add('website-pwa.icons_512x512', '/images/icons/icon-512x512.png');

        // splash
        $this->migrator->add('website-pwa.splash_640x1136', '/images/icons/splash-640x1136.png');
        $this->migrator->add('website-pwa.splash_750x1334', '/images/icons/splash-750x1334.png');
        $this->migrator->add('website-pwa.splash_828x1792', '/images/icons/splash-828x1792.png');
        $this->migrator->add('website-pwa.splash_1125x2436', '/images/icons/splash-1125x2436.png');
        $this->migrator->add('website-pwa.splash_1242x2208', '/images/icons/splash-1242x2208.png');
        $this->migrator->add('website-pwa.splash_1242x2688', '/images/icons/splash-1242x2688.png');
        $this->migrator->add('website-pwa.splash_1536x2048', '/images/icons/splash-1536x2048.png');
        $this->migrator->add('website-pwa.splash_1668x2224', '/images/icons/splash-1668x2224.png');
        $this->migrator->add('website-pwa.splash_1668x2388', '/images/icons/splash-1668x2388.png');
        $this->migrator->add('website-pwa.splash_2048x2732', '/images/icons/splash-2048x2732.png');
    }
};
