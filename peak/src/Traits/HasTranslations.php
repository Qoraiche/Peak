<?php

namespace Qoraiche\Peak\Traits;

use Qoraiche\Peak\Helpers;
use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait HasTranslations
{
    use BaseHasTranslations;

    /**
     * @return array
     */
    public function toArray()
    {
        $attributes = $this->attributesToArray(); // attributes selected by the query
        // remove attributes if they are not selected
        $translatable = array_filter($this->getTranslatableAttributes(), function ($key) use ($attributes) {
            return array_key_exists($key, $attributes);
        });

        foreach ($translatable as $field) {
            $attributes[$field] = $this->getTranslation($field, Helpers::getLocale());
        }

        return array_merge($attributes, $this->relationsToArray());
    }
}
