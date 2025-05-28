<?php

namespace Qoraiche\Peak\Traits;

use Illuminate\Support\Facades\Schema;
use Qoraiche\Peak\Helpers;

trait HasExportableData
{
    /**
     * Get exportable column names statically.
     *
     * @return array
     */
    public static function exportableDataColumnNames(): array
    {
        // Create an instance of the model
        $instance = new static;

        $columns = $instance->getExportableColumns();

        // Always return column names as an array (remove closures)
        return array_keys($columns);
    }

    /**
     * Get exportable columns for an instance.
     * By default, it generates closures that return the model's attributes.
     *
     * @return array
     */
    public function getExportableColumns(): array
    {
        // Get the column names for the model's table
        $columns = Schema::getColumnListing($this->getTable());

        // Check if the model has a $translatable property
        $isTranslatable = property_exists($this, 'translatable') && is_array($this->translatable);
        $translatable = $isTranslatable ? $this->translatable : [];

        // Map each column to a closure that retrieves the attribute from the model
        return collect($columns)->mapWithKeys(fn($column) => [
            $column => fn($model) => ($isTranslatable && in_array($column, $translatable))
                ? $model->getTranslation($column, Helpers::getLocale()) // Get translated value
                : $model->$column // Directly access attribute
        ])->toArray();
    }
}
