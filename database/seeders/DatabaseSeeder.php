<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class); // Ensure the roles are created first
        $this->call(UsersTableSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(RoadmapSeeder::class);
        $this->call(SubscriptionPlanSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(DocSeeder::class);
        $this->call(ChangelogSeeder::class);
        $this->call(RoadmapSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(SubscriptionPlanSeeder::class);
        $this->call(TicketSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(TestimonialSeeder::class);
    }
}
