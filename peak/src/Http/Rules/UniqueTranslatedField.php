<?php

namespace Qoraiche\Peak\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Qoraiche\Peak\Helpers;

class UniqueTranslatedField implements ValidationRule
{
    protected string $locale;
    protected string $modelClass;
    protected string $field;
    protected ?int $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @param string $modelClass The model class name (e.g., PostCategory)
     * @param string $field The field name (translated column) (e.g., name)
     * @param int|null $ignoreId The ID to ignore when checking uniqueness (for updating existing categories)
     * @return void
     */
    public function __construct(string $modelClass, string $field, ?int $ignoreId = null)
    {
        $this->locale = Helpers::getLocale(); // Get the current locale
        $this->modelClass = $modelClass;           // The model class (e.g., PostCategory)
        $this->field = $field;                     // The translated field (e.g., name)
        $this->ignoreId = $ignoreId;               // The category ID to ignore (for updates)
    }

    /**
     * Validate the value of the attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Start the query for uniqueness check
        $query = $this->modelClass::where("{$this->field}->{$this->locale}", $value);

        // If the ID to ignore is provided, exclude the category with that ID from the check
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        // If a category with the same name exists, fail validation
        if ($query->exists()) {
            $fail(__('The :attribute already exists in the selected locale.', ['attribute' => $this->field]));
        }
    }
}
