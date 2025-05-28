<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create English language
        Language::create([
            'name' => 'English',
            'code' => 'en',
            'flag_emoji' => '🇬🇧',
            'direction' => 'ltr',
            'default' => true, // Set English as the default language
            'active' => true,
        ]);

        // Create French language
        Language::create([
            'name' => 'French',
            'code' => 'fr',
            'flag_emoji' => '🇫🇷',
            'direction' => 'ltr',
            'default' => false,
            'active' => true,
        ]);
    }
}
