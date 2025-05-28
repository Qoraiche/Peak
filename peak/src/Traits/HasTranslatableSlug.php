<?php

namespace Qoraiche\Peak\Traits;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Models\Page;
use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait HasTranslatableSlug
{
    /**
     * Find the model by the slug for the current locale.
     *
     * @param string $slug
     * @return HasTranslatableSlug|Page|null
     */
    public static function findBySlug(string $slug): ?self
    {
        $locale = Helpers::getLocale();
        return static::where("slug->{$locale}", $slug)->first();
    }

    /**
     * Generate slugs for all locales dynamically from a translatable field.
     *
     * @param string $sourceField
     * @return void
     */
    public function generateSlugFrom(string $sourceField): void
    {
        $locales = Language::all()->pluck('code')->toArray();

        foreach ($locales as $locale) {
            $this->setTranslation('slug', $locale, Str::slug($this->getTranslation($sourceField, $locale)));
        }
    }
}
