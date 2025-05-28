<?php

namespace Qoraiche\Peak;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\ServiceProvider;

class SEOServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        SEOTools::macro('webArticle', function (string $title, string $description = null, $keywords = null, string $image = null) {
            $currentUrl = url()->current();

            $keywords = $keywords
                ? (is_array($keywords)
                    ? $keywords
                    : array_map('trim', explode(',', $keywords)))
                : null;

            SEOMeta::setTitle($title);

            if ($keywords) {
                SEOMeta::setKeywords($keywords);
            }

            if ($description) {
                SEOMeta::setDescription($description);
            }

            SEOMeta::setCanonical($currentUrl);

            OpenGraph::setTitle($title);

            if ($description) {
                OpenGraph::setDescription($description);
            }

            OpenGraph::setUrl($currentUrl);
            OpenGraph::addProperty('type', 'website');

            if ($image) {
                OpenGraph::addImage($image);
            }

            TwitterCard::setTitle($title);

            if ($description) {
                TwitterCard::setDescription($description);
            }

            if ($image) {
                TwitterCard::setImage($image);
            }
        });
    }
}
