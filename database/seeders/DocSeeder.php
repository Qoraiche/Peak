<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Doc;
use Qoraiche\Peak\Models\DocCategory;
use Qoraiche\Peak\Models\User;

class DocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have at least one user and one doc category
        $user = User::first() ?? User::factory()->create();
        $category = DocCategory::first() ?? DocCategory::factory()->create();

        Doc::create([
            'user_id' => $user->id,
            'doc_category_id' => $category->id,
            'title' => [
                'en' => 'Getting Started',
                'fr' => 'Commencer',
            ],
            'slug' => [
                'en' => 'getting-started',
                'fr' => 'commencer',
            ],
            'excerpt' => [
                'en' => 'Learn how to set up and get started with our platform.',
                'fr' => 'Apprenez à configurer et démarrer avec notre plateforme.',
            ],
            'label' => [
                'en' => 'Introduction',
                'fr' => 'Introduction',
            ],
            'body' => [
                'en' => [
                    "time" => now()->timestamp,
                    "blocks" => [
                        [
                            "type" => "header",
                            "data" => [
                                "text" => "Welcome to the Docs!",
                                "level" => 2
                            ]
                        ],
                        [
                            "type" => "paragraph",
                            "data" => [
                                "text" => "This guide will help you set up your account and understand the basics."
                            ]
                        ]
                    ],
                    "version" => "2.26.5"
                ],
                'fr' => [
                    "time" => now()->timestamp,
                    "blocks" => [
                        [
                            "type" => "header",
                            "data" => [
                                "text" => "Bienvenue dans les Docs !",
                                "level" => 2
                            ]
                        ],
                        [
                            "type" => "paragraph",
                            "data" => [
                                "text" => "Ce guide vous aidera à configurer votre compte et à comprendre les bases."
                            ]
                        ]
                    ],
                    "version" => "2.26.5"
                ],
            ],
            'headings' => [
                'en' => [
                    ['text' => 'Welcome to the Docs!', 'level' => 2]
                ],
                'fr' => [
                    ['text' => 'Bienvenue dans les Docs !', 'level' => 2]
                ],
            ],
            'order_column' => 1,
            'published' => true,
            'published_at' => now(),
        ]);
    }
}
