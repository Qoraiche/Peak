<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Release;

class ReleasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch releases from GitHub API (replace with your actual repository)
        $response = Http::get('https://api.github.com/repos/Qoraiche/laravel-mail-editor/releases');

        // Check if request is successful
        if ($response->successful()) {
            $releases = $response->json();

            foreach ($releases as $release) {
                // Skip drafts and pre-releases
                if ($release['draft'] || $release['prerelease']) {
                    continue;
                }

                // Convert the published_at to a Carbon instance
                $releaseDate = Carbon::parse($release['published_at'])->format('Y-m-d H:i:s');

                // Insert release data into the database
                Release::create([
                    'version' => $release['tag_name'], // e.g., "v1.0.0"
                    'title' => $release['name'], // e.g., "Initial Release"
                    'description' => $release['body'], // Release description
                    'release_date' => $releaseDate, // Release date
                    'download_url' => $release['zipball_url'], // GitHub's zipball URL
                ]);
            }
        } else {
            // Log or handle error if the API request fails
            \Log::error('Failed to fetch releases from GitHub');
        }
    }
}
