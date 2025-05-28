<?php

namespace Qoraiche\Peak\Traits;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Exception;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Services\MailTemplateManager;

trait MailContentTrait
{
    /**
     * @param string $slug
     * @param array $data
     * @return Content
     * @throws Exception
     */
    public function databaseContent(string $slug, array $data = []): Content
    {
        // Get the current language dynamically using app()->getLocale()
        $language = Helpers::getLocale();

        // Get the email template for the given language, fallback to default if not found
        $template = app(MailTemplateManager::class)->getTemplate($slug, $language);

        if ($template) {
            $body = $this->replacePlaceholders($template->body ?? '', $data);

            return new Content(
                markdown: 'emails.dynamic-markdown',
                with: [
                    'content' => $body
                ]
            );
        }

        throw new Exception("Email template for '{$slug}' not found in language '{$language}'");
    }

    /**
     * @param string $slug
     * @param array $data
     * @return Envelope
     * @throws Exception
     */
    public function databaseEnvelope(string $slug, array $data = []): Envelope
    {
        // Get the current language dynamically using app()->getLocale()
        $language = app()->getLocale();

        // Get the email template for the given language, fallback to default if not found
        $template = app(MailTemplateManager::class)->getTemplate($slug, $language);

        if ($template) {
            $fromEmail = $template->from_email ?? config('mail.from.address');
            $fromName = $template->from_name ?? config('mail.from.name');
            $subject = $this->replacePlaceholders($template->subject ?? '', $data);

            return new Envelope(
                from: new Address($fromEmail, $fromName),
                subject: $subject
            );
        }

        throw new Exception("Email template for '{$slug}' not found in language '{$language}'");
    }

    /**
     * @param string $content
     * @param array $data
     * @return string
     */
    protected function replacePlaceholders(string $content, array $data = []): string
    {
        if (empty($content)) {
            return '';
        }

        // Convert Blade-style {{ $user->profile->address->city }} to {{ user.profile.address.city }}
        $content = preg_replace_callback('/\{\{\s*\$([a-zA-Z0-9_]+)(->([a-zA-Z0-9_]+))*\s*\}\}/', function ($matches) {
            // Convert -> to .
            $path = str_replace('->', '.', $matches[0]);

            // Remove the $ symbol and spaces from {{ $user->name }} to {{ user.name }}
            return preg_replace('/\{\{\s*\$([a-zA-Z0-9_]+)(\.[a-zA-Z0-9_]+)*\s*\}\}/', '{{ $1$2 }}', $path);
        }, $content);

        // Replace placeholders using data_get() to support infinite nesting
        preg_match_all('/\{\{\s*([a-zA-Z0-9_\.]+)\s*\}\}/', $content, $matches);

        foreach ($matches[1] as $placeholder) {
            // Get the value of the placeholder from the nested data
            $value = data_get($data, $placeholder);

            // Replace only if the value exists (to avoid showing raw {{ placeholder }} in email)
            if (!is_null($value)) {
                $content = str_replace('{{ ' . $placeholder . ' }}', (string)$value, $content);
            }
        }

        return $content;
    }
}
