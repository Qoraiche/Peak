<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Menu;
use Qoraiche\Peak\Models\Page;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'order_column' => 1,
                'title' => ['en' => 'Terms of Service', 'fr' => "Conditions d'utilisation"],
                'page_id' => Page::first()?->id,
                'type' => 'page',
                'template_name' => null,
                'external_link' => ['fr' => null],
                'internal_path' => null,
                'open_new_window' => false,
                'group_name' => 'footer',
            ],
            [
                'order_column' => 2,
                'title' => ['en' => 'Privacy Policy', 'fr' => 'Politique de confidentialité'],
                'page_id' => Page::first()?->id,
                'type' => 'page',
                'template_name' => null,
                'external_link' => ['fr' => null],
                'internal_path' => null,
                'open_new_window' => false,
                'group_name' => 'footer',
            ],
            [
                'order_column' => 3,
                'title' => ['en' => 'Cookie Policy', 'fr' => 'Politique relative aux cookies'],
                'page_id' => Page::first()?->id,
                'type' => 'page',
                'template_name' => null,
                'external_link' => ['fr' => null],
                'internal_path' => null,
                'open_new_window' => false,
                'group_name' => 'footer',
            ],
            [
                'order_column' => 4,
                'title' => ['en' => 'Changelog', 'fr' => 'Journal des modifications'],
                'page_id' => null,
                'type' => 'template',
                'template_name' => 'changelog',
                'external_link' => ['fr' => null],
                'internal_path' => null,
                'open_new_window' => false,
                'group_name' => 'footer',
            ],
            [
                'order_column' => 5,
                'title' => ['en' => 'Contact'],
                'type' => 'template',
                'template_name' => 'contact',
                'external_link' => ['en' => null],
                'group_name' => 'footer',
                'open_new_window' => false,
            ],
            [
                'order_column' => 6,
                'title' => ['en' => 'Support'],
                'type' => 'template',
                'template_name' => 'support',
                'external_link' => ['en' => null],
                'group_name' => 'footer',
                'open_new_window' => false,
            ],
            [
                'order_column' => 7,
                'title' => ['en' => 'Blog'],
                'type' => 'template',
                'template_name' => 'blog',
                'external_link' => ['en' => null],
                'group_name' => 'header',
                'open_new_window' => false,
            ],
            [
                'order_column' => 8,
                'title' => ['en' => 'Documentation'],
                'type' => 'template',
                'template_name' => 'documentation',
                'external_link' => ['en' => null],
                'group_name' => 'header',
                'open_new_window' => false,
            ],
            [
                'order_column' => 9,
                'title' => ['en' => 'Roadmap', 'fr' => 'Feuille de route'],
                'type' => 'template',
                'template_name' => 'roadmap',
                'external_link' => ['fr' => null],
                'group_name' => 'header',
                'open_new_window' => false,
            ],
            [
                'order_column' => 10,
                'title' => ['en' => 'About', 'fr' => 'À propos'],
                'type' => 'template',
                'template_name' => 'about',
                'external_link' => ['fr' => null],
                'group_name' => 'footer',
                'open_new_window' => false,
            ],
            [
                'order_column' => 11,
                'title' => ['en' => 'Features', 'fr' => 'Fonctionnalités'],
                'type' => 'link',
                'external_link' => ['en' => '/#features', 'fr' => '/#features'],
                'group_name' => 'header',
                'open_new_window' => false,
            ],
            [
                'order_column' => 12,
                'title' => ['en' => 'Releases', 'fr' => 'Versions'],
                'type' => 'template',
                'template_name' => 'changelog',
                'external_link' => ['fr' => null],
                'group_name' => 'header',
                'open_new_window' => false,
            ],
            [
                'order_column' => 13,
                'title' => ['en' => 'Pricing', 'fr' => 'Tarification'],
                'type' => 'link',
                'external_link' => ['en' => '/#pricing', 'fr' => '/#pricing'],
                'group_name' => 'header',
                'open_new_window' => false,
            ]
        ];

        foreach ($menus as $menu) {
            Menu::query()->create($menu);
        }
    }
}
