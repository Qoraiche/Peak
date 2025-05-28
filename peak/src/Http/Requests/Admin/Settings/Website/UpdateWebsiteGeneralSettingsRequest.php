<?php

namespace Qoraiche\Peak\Http\Requests\Admin\Settings\Website;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteGeneralSettingsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_title' => 'string|required',
            'tagline' => 'string|required',
            'title_separator' => 'string|required',
            'tos_url' => 'url|nullable',
            'privacy_policy_url' => 'url|nullable',
            'default_language' => 'nullable',
            'currency_code' => [
                'required',
                Rule::in(global_currencies())
            ],
            'timezone' => [
                'required',
                Rule::in(global_timezones())
            ],
            'company_name' => 'string|nullable',
            'company_address' => 'string|nullable',
            'company_phone' => 'string|nullable',
            'contact_email' => 'email|nullable',
            'seo_description' => 'string|nullable',
            'seo_keywords' => 'string|nullable'
        ];
    }
}
