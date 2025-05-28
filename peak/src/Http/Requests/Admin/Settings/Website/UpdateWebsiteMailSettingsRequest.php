<?php

namespace Qoraiche\Peak\Http\Requests\Admin\Settings\Website;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteMailSettingsRequest extends FormRequest
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
        /**
         * mailer: props.mailer,
         * host: props.host,
         * port: props.port,
         * username: props.username,
         * password: props.password,
         * encryption: props.encryption,
         * from_address: props.from_address,
         * from_name: props.from_name,
         * sendmail_path: props.sendmail_path,
         * mailgun_domain: props.mailgun_domain,
         * mailgun_secret: props.mailgun_secret,
         * mailgun_endpoint: props.mailgun_endpoint, // api.eu.mailgun.net
         * postmark_token: props.postmark_token,
         * resend_key: props.resend_key,
         * ses_key: props.ses_key,
         * ses_secret: props.ses_secret,
         * ses_region: props.ses_region, // us-east-1
         * mailersend_api_key: props.mailersend_api_key,
         */
        return [
            'mailer' => 'required|in:smtp,sendmail,mailgun,postmark,resend,ses,mailersend',
            'host' => 'required_if:mailer,smtp',
            'sendmail_path' => 'nullable|string|required_if:mailer,sendmail',
            'port' => 'required_if:mailer,smtp|numeric',
            'username' => 'required_if:mailer,smtp',
            'password' => 'required_if:mailer,smtp',
            'encryption' => 'required_if:mailer,smtp',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
            'mailgun_domain' => 'nullable|string|required_if:mailer,mailgun',
            'mailgun_secret' => 'nullable|string|required_if:mailer,mailgun',
            'mailgun_endpoint' => 'nullable|string|required_if:mailer,mailgun',
            'postmark_token' => 'nullable|required_if:mailer,postmark',
            'resend_key' => 'nullable|required_if:mailer,resend',
            'ses_key' => 'nullable|required_if:mailer,ses',
            'ses_secret' => 'nullable|required_if:mailer,ses',
            'ses_region' => 'nullable|required_if:mailer,ses',
            'mailersend_api_key' => 'nullable|required_if:mailer,mailersend',
        ];
    }
}
