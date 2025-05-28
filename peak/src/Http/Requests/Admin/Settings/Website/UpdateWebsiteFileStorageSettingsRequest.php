<?php

namespace Qoraiche\Peak\Http\Requests\Admin\Settings\Website;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteFileStorageSettingsRequest extends FormRequest
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
            'disk' => 'required|in:local,public,s3',
            's3_key' => 'string|required_if:disk,s3',
            's3_secret' => 'string|required_if:disk,s3',
            's3_region' => 'string|required_if:disk,s3',
            's3_bucket' => 'string|required_if:disk,s3',
            's3_url' => 'nullable|string',
            's3_endpoint' => 'nullable|string'
        ];
    }
}
