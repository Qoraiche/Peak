<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Changelog;
use Qoraiche\Peak\Models\User;

class ChangelogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure there's at least one user
        $user = User::first() ?? User::factory()->create();

        Changelog::create([
            'user_id' => $user->id,
            'title' => [
                'en' => 'Initial Release',
                'fr' => 'Première Version',
            ],
            'body' => [
                'en' => 'We are excited to launch our application!',
                'fr' => 'Nous sommes ravis de lancer notre application !',
            ],
            'published' => true,
            'published_at' => now(),
        ]);

        Changelog::create([
            'user_id' => $user->id,
            'title' => [
                'en' => 'Bug Fixes',
                'fr' => 'Corrections de bogues',
            ],
            'body' => [
                'en' => 'Fixed login issues and improved performance.',
                'fr' => 'Correction des problèmes de connexion et amélioration des performances.',
            ],
            'published' => true,
            'published_at' => now()->subDays(2),
        ]);
    }
}
