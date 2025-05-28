<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Qoraiche\Peak\Models\Page;
use Qoraiche\Peak\Models\User;
use Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $pages = [
            [
                'title' => ['en' => 'Terms of Service', 'fr' => 'Conditions d\'utilisation'],
                'body' => [
                    'en' => '<h2>Terms</h2><p>By using our service, you agree to these terms...</p>',
                    'fr' => '<h2>Conditions</h2><p>En utilisant notre service, vous acceptez ces conditions...</p>',
                ],
                'meta_description' => ['en' => 'Terms and conditions of using our service.', 'fr' => 'Conditions générales d\'utilisation.'],
                'meta_keywords' => ['en' => 'terms, service, agreement', 'fr' => 'conditions, service, accord'],
            ],
            [
                'title' => ['en' => 'Privacy Policy', 'fr' => 'Politique de confidentialité'],
                'body' => [
                    'en' => '<h2>Privacy</h2><p>We value your privacy and handle your data responsibly...</p>',
                    'fr' => '<h2>Confidentialité</h2><p>Nous respectons votre vie privée et traitons vos données de manière responsable...</p>',
                ],
                'meta_description' => ['en' => 'How we handle user data and privacy.', 'fr' => 'Comment nous gérons les données et la vie privée des utilisateurs.'],
                'meta_keywords' => ['en' => 'privacy, data, security', 'fr' => 'confidentialité, données, sécurité'],
            ],
            [
                'title' => ['en' => 'Cookie Policy', 'fr' => 'Politique relative aux cookies'],
                'body' => [
                    'en' => '<h2>Cookies</h2><p>We use cookies to improve your experience...</p>',
                    'fr' => '<h2>Cookies</h2><p>Nous utilisons des cookies pour améliorer votre expérience...</p>',
                ],
                'meta_description' => ['en' => 'Details about how we use cookies.', 'fr' => 'Détails sur l\'utilisation des cookies.'],
                'meta_keywords' => ['en' => 'cookies, tracking, policy', 'fr' => 'cookies, suivi, politique'],
            ],
        ];

        foreach ($pages as $page) {
            Page::create([
                'user_id' => $user->id,
                'title' => $page['title'],
                'slug' => [
                    'en' => Str::slug($page['title']['en']),
                    'fr' => Str::slug($page['title']['fr']),
                ],
                'body' => $page['body'],
                'meta_description' => $page['meta_description'],
                'meta_keywords' => $page['meta_keywords'],
                'published' => true,
                'published_at' => Carbon::now(),
            ]);
        }
    }
}
