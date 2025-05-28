<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-headings.features_heading', ['en' => 'Features']);
        $this->migrator->add('frontend-headings.pricing_heading', ['en' => 'Pricing']);
        $this->migrator->add('frontend-headings.testimonials_heading', ['en' => 'Testimonials']);
        $this->migrator->add('frontend-headings.faqs_heading', ['en' => 'FAQs']);
    }
};
