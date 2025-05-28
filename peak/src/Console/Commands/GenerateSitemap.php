<?php

namespace Qoraiche\Peak\Console\Commands;

use Illuminate\Console\Command;
use Qoraiche\Peak\Models\Doc;
use Qoraiche\Peak\Models\Page;
use Qoraiche\Peak\Models\Post;
use Qoraiche\Peak\Models\Roadmap;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        try {
            // Create the sitemap instance
            $sitemap = Sitemap::create();

            // List of sitemapable models
            $models = [
                Post::class,
                Page::class,
                Doc::class,
                Roadmap::class,
            ];

            // Write the sitemap to file
            $path = public_path('sitemap.xml');

            SitemapGenerator::create(config('app.url'))->writeToFile($path);

            // Loop through each model and add entries
            foreach ($models as $model) {
                $this->info("Adding entries for: " . class_basename($model));

                foreach ($model::query()->cursor() as $item) {
                    $sitemap->add($item); // Assumes each model implements Sitemapable
                }
            }

            $sitemap->writeToFile($path);

            $this->info("Sitemap generated successfully at: {$path}");

        } catch (\Exception $e) {
            $this->error('Failed to generate sitemap: ' . $e->getMessage());
        }
    }
}
