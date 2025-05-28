<?php

namespace Qoraiche\Peak\Services;

use Qoraiche\Peak\Helpers;
use Spatie\LaravelSettings\Settings;

abstract class TranslatableBaseSettings extends Settings
{
    /**
     * Get a translatable value for the current locale, falling back to 'en' if not found.
     */
    public function getTranslatable(string $field)
    {
        $locale = Helpers::getLocale();

        // Try to get the value for the current locale
        $value = $this->{$field}[$locale] ?? null;

        // If the value is not found, fallback to 'en'
        if ($value === null) {
            $value = $this->{$field}['en'] ?? null;
        }

        return $value;
    }

    /**
     * Set a translatable value for the current locale.
     */
    public function setTranslatable(string $field, $value)
    {
        $locale = Helpers::getLocale();

        if (!isset($this->{$field})) {
            $this->{$field} = [];
        }
        $this->{$field}[$locale] = $value;
    }
}
