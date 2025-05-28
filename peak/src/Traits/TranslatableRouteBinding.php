<?php

namespace Qoraiche\Peak\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Qoraiche\Peak\Helpers;

trait TranslatableRouteBinding
{
    /**
     * Resolve route binding for translatable attributes.
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Only resolve binding for non-admin routes
        if ($this->isAdminRoute()) {
            return parent::resolveRouteBinding($value, $field); // Let default binding handle admin routes
        }

        // Proceed with translatable route binding for non-admin routes
        $field = $field ?? $this->getTranslatableRouteKey(); // Default to slug
        $locale = Helpers::getLocale();
        $fallbackLocale = config('app.fallback_locale');

        // Try to find the model using the current locale
        $model = $this->where("{$field}->{$locale}", $value)->first();

        // If not found, try the fallback locale
        if (!$model && $fallbackLocale !== $locale) {
            $model = $this->where("{$field}->{$fallbackLocale}", $value)->first();
        }

        // If still not found, search in all translations and redirect
        if (!$model) {
            $model = $this->whereJsonContains($field, $value)->first();

            if ($model) {
                $availableLocale = collect($model->getTranslations($field))
                    ->search(fn($slug) => $slug === $value);

                if ($availableLocale) {
                    return Redirect::route(request()->route()?->getName(), [
                        'lang' => $availableLocale,
                        $this->getRouteKeyName() => $model
                    ]);
                }
            }
        }

        return $model ?? abort(404);
    }

    /**
     * Check if the current route is an admin route.
     *
     * @return bool
     */
    protected function isAdminRoute(): bool
    {
        return Route::is('admin.*');
    }

    /**
     * Get the translatable route key (default to 'slug').
     */
    protected function getTranslatableRouteKey(): string
    {
        return property_exists($this, 'translatableRouteKey') ? $this->translatableRouteKey : 'slug';
    }
}
