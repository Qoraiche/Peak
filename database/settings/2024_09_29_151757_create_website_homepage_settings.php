<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-homepage.show_featured_streams', true);
        $this->migrator->add('website-homepage.top_live_categories', [1, 2]);
        $this->migrator->add('website-homepage.show_top_live_categories', true);
        $this->migrator->add('website-homepage.show_top_live_groups', true);
        $this->migrator->add('website-homepage.show_top_live_streams_by_category', true);
    }
};
