<?php

namespace Qoraiche\Peak\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCategoryId implements ValidationRule
{
    /**
     * Validate the given value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if value is a valid numeric category ID (for existing categories)
        if (is_numeric($value)) {
            return;
        }

        // Check if the value matches the "new-xxxx" format (for new categories)
        if (!preg_match('/^new-\d+$/', $value)) {
            // If validation fails, pass an error message via the $fail closure
            $fail('The :attribute must be a valid category ID');
        }
    }
}
