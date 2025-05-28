<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the admin roles from config (this is an array)
        $adminRoleNames = config('peak.default_roles.admin', []);

        $user = User::factory(1)->create([
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ])->first();

        // Assign roles to the user (roles should already exist from the roles seeder)
        foreach ($adminRoleNames as $roleName) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role) {
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleName);
                    Log::info("Assigned role '{$roleName}' to user '{$user->name}'.");
                }
            } else {
                Log::warning("Role '{$roleName}' does not exist.");
            }
        }
    }
}
