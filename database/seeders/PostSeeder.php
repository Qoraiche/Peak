<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Qoraiche\Peak\Models\Post;
use Qoraiche\Peak\Models\User;
use Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        Post::create([
            'user_id' => $user->id,
            'title' => [
                'en' => 'Top 10 Tips for a Productive Day',
                'fr' => 'Top 10 des conseils pour une journée productive',
            ],
            'slug' => [
                'en' => Str::slug('Top 10 Tips for a Productive Day'),
                'fr' => Str::slug('Top 10 des conseils pour une journée productive'),
            ],
            'body' => [
                'en' => '<h2>Stay Organized</h2><p>Use a planner to map out your day...</p>',
                'fr' => '<h2>Restez organisé</h2><p>Utilisez un agenda pour planifier votre journée...</p>',
            ],
            'excerpt' => [
                'en' => 'Discover the best productivity tips to maximize your day.',
                'fr' => 'Découvrez les meilleurs conseils de productivité pour maximiser votre journée.',
            ],
            'meta_description' => [
                'en' => 'Learn how to be more productive with these 10 simple tips.',
                'fr' => 'Apprenez à être plus productif avec ces 10 conseils simples.',
            ],
            'meta_keywords' => [
                'en' => 'productivity, tips, time management',
                'fr' => 'productivité, conseils, gestion du temps',
            ],
            'published' => true,
            'published_at' => Carbon::now(),
            'featured' => true,
        ]);

        Post::create([
            'user_id' => $user->id,
            'title' => [
                'en' => 'How to Stay Motivated When Working from Home',
                'fr' => 'Comment rester motivé en travaillant à domicile',
            ],
            'slug' => [
                'en' => Str::slug('How to Stay Motivated When Working from Home'),
                'fr' => Str::slug('Comment rester motivé en travaillant à domicile'),
            ],
            'body' => [
                'en' => '<h2>Create a Workspace</h2><p>Dedicate a specific area for work to stay focused...</p>',
                'fr' => '<h2>Créer un espace de travail</h2><p>Définissez un espace spécifique pour rester concentré...</p>',
            ],
            'excerpt' => [
                'en' => 'Simple ways to keep your motivation high while working remotely.',
                'fr' => 'Des moyens simples de rester motivé en travaillant à distance.',
            ],
            'meta_description' => [
                'en' => 'Find out how to stay productive and motivated at home.',
                'fr' => 'Découvrez comment rester productif et motivé à domicile.',
            ],
            'meta_keywords' => [
                'en' => 'remote work, motivation, work from home',
                'fr' => 'travail à distance, motivation, télétravail',
            ],
            'published' => true,
            'published_at' => Carbon::now(),
            'featured' => false,
        ]);

        Post::create([
            'user_id' => $user->id,
            'title' => [
                'en' => 'Top 5 SaaS Tools to Boost Team Productivity',
                'fr' => 'Top 5 des outils SaaS pour booster la productivité de l’équipe',
            ],
            'slug' => [
                'en' => Str::slug('Top 5 SaaS Tools to Boost Team Productivity'),
                'fr' => Str::slug('Top 5 des outils SaaS pour booster la productivité de l’équipe'),
            ],
            'body' => [
                'en' => '<h2>1. Slack</h2><p>Keep communication flowing smoothly within your team...</p><h2>2. Trello</h2><p>Organize tasks with visual boards...</p>',
                'fr' => '<h2>1. Slack</h2><p>Gardez une communication fluide au sein de votre équipe...</p><h2>2. Trello</h2><p>Organisez les tâches avec des tableaux visuels...</p>',
            ],
            'excerpt' => [
                'en' => 'Explore the best SaaS tools that help remote teams collaborate efficiently.',
                'fr' => 'Découvrez les meilleurs outils SaaS pour une collaboration efficace à distance.',
            ],
            'meta_description' => [
                'en' => 'Discover essential SaaS tools that enhance your team’s workflow and productivity.',
                'fr' => 'Découvrez les outils SaaS essentiels pour améliorer le flux de travail de votre équipe.',
            ],
            'meta_keywords' => [
                'en' => 'SaaS tools, productivity, remote team',
                'fr' => 'outils SaaS, productivité, équipe distante',
            ],
            'published' => true,
            'published_at' => Carbon::now(),
            'featured' => true,
        ]);
    }
}
