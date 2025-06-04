<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class); // Ensure the roles are created first
        $this->call(UsersTableSeeder::class);
        $this->call(LanguageSeeder::class);
    }
}
