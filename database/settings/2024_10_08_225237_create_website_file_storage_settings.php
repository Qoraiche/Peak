<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('website-file-storage.disk', 'public');
        // s3
        $this->migrator->add('website-file-storage.s3_key', null);
        $this->migrator->add('website-file-storage.s3_secret', null);
        $this->migrator->add('website-file-storage.s3_region', null);
        $this->migrator->add('website-file-storage.s3_bucket', null);
        $this->migrator->add('website-file-storage.s3_url', null);
        $this->migrator->add('website-file-storage.s3_endpoint', null);
    }
};
