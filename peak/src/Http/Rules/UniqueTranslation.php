<?php

namespace Qoraiche\Peak\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueTranslation implements ValidationRule
{
    protected string $model;
    protected string $column;
    protected string $locale;
    protected ?int $ignoreId;

    /**
     * @param string $model
     * @param string $column
     * @param string|null $locale
     * @param int|null $ignoreId
     */
    public function __construct(string $model, string $column, ?string $locale = null, ?int $ignoreId = null)
    {
        $this->model = $model; // Eloquent model (e.g., PostCategory::class)
        $this->column = $column; // Translatable column (e.g., 'name')
        $this->locale = $locale ?? current_session_locale(); // Current language
        $this->ignoreId = $ignoreId; // Ignore ID (for updates)
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = $this->model::where("{$this->column}->{$this->locale}", $value);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail(__('The :attribute has already been taken in ' . strtoupper($this->locale) . '.'));
        }
    }
}
