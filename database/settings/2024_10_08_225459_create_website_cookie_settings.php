<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-cookie.cookie_consent_enabled', true);
        $this->migrator->add('website-cookie.cookie_consent_name', 'cookie_consent');
        $this->migrator->add('website-cookie.cookie_consent_text', [
            'en' => 'We use cookies to enhance your experience and deliver personalized content. By clicking "Allow Cookies", you consent to our use of cookies.',
            'fr' => 'Nous utilisons des cookies pour améliorer votre expérience et vous proposer un contenu personnalisé. En cliquant sur "Autoriser les cookies", vous consentez à leur utilisation.',
        ]);
        $this->migrator->add('website-cookie.cookie_consent_lifetime', 360);
        $this->migrator->add('website-cookie.cookie_consent_policy_page_url', ['en' => '']);
    }
};
