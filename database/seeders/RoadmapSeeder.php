<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Qoraiche\Peak\Models\Roadmap;
use Qoraiche\Peak\Models\Tag;
use Qoraiche\Peak\Models\User;
use Str;

class RoadmapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create user
        $user = User::first() ?? User::factory()->create();

        // Roadmap types and statuses by locale
        $types = [
            'en' => [
                'Feature Request', 'Improvement', 'Bug Fix', 'Technical Task', 'Integration',
                'UI/UX Update', 'Performance', 'Security', 'Compliance', 'Research/Exploration',
            ],
            'fr' => [
                'Demande de fonctionnalité', 'Amélioration', 'Correction de bug', 'Tâche technique', 'Intégration',
                'Mise à jour UI/UX', 'Performance', 'Sécurité', 'Conformité', 'Recherche / Exploration',
            ],
        ];

        $statuses = [
            'en' => [
                'Planned', 'In Progress', 'In Review', 'Completed', 'Released', 'Postponed'
            ],
            'fr' => [
                'Prévu', 'En cours', 'En révision', 'Terminé', 'Publié', 'Reporté'
            ],
        ];

        // 1. Create all translated tags
        foreach (['en', 'fr'] as $locale) {
            foreach ($types[$locale] as $index => $type) {
                Tag::findOrCreate([$locale => $type], "roadmap_type_$locale");
            }

            foreach ($statuses[$locale] as $index => $status) {
                Tag::findOrCreate([$locale => $status], "roadmap_status_$locale");
            }
        }

        // 2. Define roadmaps to seed
        $roadmaps = [
            [
                'title_en' => 'Laravel 11 Learning Roadmap',
                'title_fr' => 'Feuille de route pour apprendre Laravel 11',
                'body_en' => '<h2>Start with Basics</h2><p>Understand routing, controllers, and views...</p>',
                'body_fr' => '<h2>Commencez par les bases</h2><p>Comprenez les routes, les contrôleurs et les vues...</p>',
            ],
            [
                'title_en' => 'Mastering Vue 3 with Composition API',
                'title_fr' => 'Maîtriser Vue 3 avec l’API Composition',
                'body_en' => '<h2>Introduction to Composition API</h2><p>Learn setup(), reactive(), and computed() basics...</p>',
                'body_fr' => '<h2>Introduction à l’API Composition</h2><p>Apprenez les bases de setup(), reactive(), et computed()...</p>',
            ],
        ];

        // 3. Create roadmaps and assign one random type and status per locale
        foreach ($roadmaps as $data) {
            $roadmap = Roadmap::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => [
                    'en' => $data['title_en'],
                    'fr' => $data['title_fr'],
                ],
                'slug' => [
                    'en' => Str::slug($data['title_en']),
                    'fr' => Str::slug($data['title_fr']),
                ],
                'body' => [
                    'en' => $data['body_en'],
                    'fr' => $data['body_fr'],
                ],
                'published' => true,
                'published_at' => Carbon::now(),
            ]);

            foreach (['en', 'fr'] as $locale) {
                $type = collect($types[$locale])->random();
                $status = collect($statuses[$locale])->random();

                $roadmap->attachTag($type, "roadmap_type_$locale");
                $roadmap->attachTag($status, "roadmap_status_$locale");
            }
        }
    }
}
