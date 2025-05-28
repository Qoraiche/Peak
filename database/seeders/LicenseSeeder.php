<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\License;
use Faker\Factory as Faker;
use Carbon\Carbon;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a Faker instance for generating random data
        $faker = Faker::create();

        // Get a list of users who will have licenses
        $users = User::all();

        foreach ($users as $user) {
            // You can adjust how many licenses per user you want to create
            for ($i = 0; $i < 2; $i++) {
                License::query()->create([
                    'user_id' => $user->id, // Associate the license with the user
                    'license_key' => strtoupper($faker->lexify('????-????-????-????')), // Unique license key (e.g., "ABCD-EFGH-IJKL-MNOP")
                    'license_type' => $faker->randomElement(['pro', 'basic', 'enterprise']), // License type (e.g., "pro", "basic", "enterprise")
                    'plan' => $faker->randomElement(['Pro Lifetime', 'Basic Monthly', 'Enterprise Annual']), // Plan type (e.g., "Pro Lifetime")
                    'purchase_date' => Carbon::now()->subDays(rand(1, 1000))->format('Y-m-d'), // Random purchase date within the last 1000 days
                    'expires_at' => Carbon::now()->addMonths(rand(1, 12))->format('Y-m-d'), // Random expiration date within the next 12 months
                    'status' => $faker->randomElement(['active', 'inactive', 'expired']), // Random license status
                ]);
            }
        }
    }
}
