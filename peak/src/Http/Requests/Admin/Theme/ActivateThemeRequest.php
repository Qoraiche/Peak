<?php

namespace Qoraiche\Peak\Http\Requests\Admin\Theme;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Qoraiche\Peak\Services\ThemeService;

class ActivateThemeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'theme' => ['string', 'required', function ($attribute, $value, $fail) {
                // Use the themeExists method from ThemeService
                $themeService = app(ThemeService::class);

                // Check if the theme exists
                if (!$themeService->themeExists($value)) {
                    $fail('The selected theme does not exist.');
                }
            }]
        ];
    }
}
