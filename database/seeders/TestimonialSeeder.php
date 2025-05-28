<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Testimonial::create([
            'name' => [
                'en' => 'Alice Brown',
                'fr' => 'Alice Brown',
            ],
            'rating' => 5,
            'company_name' => [
                'en' => 'Creative Solutions',
                'fr' => 'Solutions Créatives',
            ],
            'comment' => [
                'en' => 'Absolutely fantastic service, exceeded my expectations!',
                'fr' => 'Service absolument fantastique, a dépassé mes attentes!',
            ],
        ]);

        Testimonial::create([
            'name' => [
                'en' => 'David Keller',
                'fr' => 'David Keller',
            ],
            'rating' => 5,
            'company_name' => [
                'en' => 'Atlas Marketing',
                'fr' => 'Marketing Atlas',
            ],
            'comment' => [
                'en' => 'The dashboard is clean and intuitive. We scaled our operations 2x faster.',
                'fr' => 'Le tableau de bord est clair et intuitif. Nous avons doublé notre croissance.',
            ],
        ]);

        Testimonial::create([
            'name' => [
                'en' => 'Fatima El Yazidi',
                'fr' => 'Fatima El Yazidi',
            ],
            'rating' => 4,
            'company_name' => [
                'en' => 'DataXpress',
                'fr' => 'DataXpress',
            ],
            'comment' => [
                'en' => 'Reliable cloud performance and great customer support.',
                'fr' => 'Performance cloud fiable et excellent support client.',
            ],
        ]);

        Testimonial::create([
            'name' => [
                'en' => 'Julien Laurent',
                'fr' => 'Julien Laurent',
            ],
            'rating' => 5,
            'company_name' => [
                'en' => 'Finovate',
                'fr' => 'Finovate',
            ],
            'comment' => [
                'en' => 'Our team collaboration improved drastically thanks to the integrations.',
                'fr' => 'La collaboration de notre équipe s\'est grandement améliorée grâce aux intégrations.',
            ],
        ]);

        Testimonial::create([
            'name' => [
                'en' => 'Maria Gonzalez',
                'fr' => 'Maria Gonzalez',
            ],
            'rating' => 4,
            'company_name' => [
                'en' => 'LegalSoft',
                'fr' => 'LegalSoft',
            ],
            'comment' => [
                'en' => 'Time-saving features that automate our daily workflow.',
                'fr' => 'Des fonctionnalités qui nous font gagner du temps au quotidien.',
            ],
        ]);

        Testimonial::create([
            'name' => [
                'en' => 'Ahmed Benali',
                'fr' => 'Ahmed Benali',
            ],
            'rating' => 5,
            'company_name' => [
                'en' => 'SmartRetail',
                'fr' => 'SmartRetail',
            ],
            'comment' => [
                'en' => 'The analytics tools gave us real insights into our customer behavior.',
                'fr' => 'Les outils d’analyse nous ont donné une vraie vision du comportement client.',
            ],
        ]);
    }
}
