<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('frontend-hero.image_path', []);
        $this->migrator->add('frontend-hero.heading_1_text', ['en' => 'Turn Ideas into Reality—Fast.']);
        $this->migrator->add('frontend-hero.heading_2_text', ['en' => 'All-in-one platform to manage features, track updates, and showcase customer success—beautifully and effortlessly.']);
        $this->migrator->add('frontend-hero.login_button_text', ['en' => 'Login']);
        $this->migrator->add('frontend-hero.register_button_text', ['en' => 'Register']);
    }
};
