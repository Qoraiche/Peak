<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Feature::create([
            'order_column' => 1,
            'name' => [
                'en' => 'Real-time Notifications',
                'fr' => 'Notifications en temps réel',
            ],
            'description' => [
                'en' => 'Get notified instantly about important updates and messages.',
                'fr' => 'Recevez instantanément des notifications sur les mises à jour importantes et les messages.',
            ],
        ]);

        Feature::create([
            'order_column' => 2,
            'name' => [
                'en' => 'Dark Mode',
                'fr' => 'Mode sombre',
            ],
            'description' => [
                'en' => 'Switch to a dark theme for a better nighttime experience.',
                'fr' => 'Passez à un thème sombre pour une meilleure expérience nocturne.',
            ],
        ]);

        Feature::create([
            'order_column' => 3,
            'name' => [
                'en' => 'Custom Profiles',
                'fr' => 'Profils personnalisés',
            ],
            'description' => [
                'en' => 'Personalize your profile with custom images and bios.',
                'fr' => 'Personnalisez votre profil avec des images et des biographies personnalisées.',
            ],
        ]);

        Feature::create([
            'order_column' => 4,
            'name' => [
                'en' => 'Offline Mode',
                'fr' => 'Mode hors ligne',
            ],
            'description' => null, // Nullable field
        ]);
    }
}
