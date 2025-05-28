<?php

namespace Qoraiche\Peak\Traits;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;
use Spatie\Tags\Tag;

trait HasLocalizedTags
{
    /**
     * @return mixed
     */
    public function getLocalizedTagsAttribute()
    {
        $currentLanguage = current_session_locale();

        return $this->tags?->map(function (Tag $tag) use ($currentLanguage) {
            return [
                'id' => $tag->id,
                'name' => $tag->name[$currentLanguage] ?? $tag->name['en'], // Fallback to English
                'type' => $tag->type,
            ];
        });
    }
}
