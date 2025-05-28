<?php

namespace Qoraiche\Peak\Traits;

use Qoraiche\Peak\Models\NotificationTemplate;

trait NotificationDataTrait
{
    /**
     * Retrieve notification data based on the slug.
     *
     * @param string $slug
     * @param string|null $language
     * @return array
     */
    public function getNotificationDataBySlug(string $slug, ?string $language = null): array
    {
        // Retrieve notification data from the database
        $notificationData = NotificationTemplate::query()->where('slug', $slug)->where('language', $language ?? app()->getLocale())->first();

        // Fallback values if no data is found
        $icon = $notificationData->icon ?? '/storage/users/default.png';
        $body = $notificationData->body ?? 'This is a default notification body.';

        return [
            'icon' => $icon,
            'body' => $body,
        ];
    }

    /**
     * Replace placeholders in the content using data.
     *
     * @param string $content
     * @param array $data
     * @return string
     */
    protected function replacePlaceholders(string $content, array $data = []): string
    {
        if (empty($content)) {
            return '';
        }

        // Replace placeholders using Blade-style {{ $user->name }} or {{ user.name }}
        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_\.]+)\s*\}\}/', function ($matches) use ($data) {
            $placeholder = $matches[1];
            // Use data_get to retrieve the value from the data array using dot notation
            $value = data_get($data, $placeholder);
            return $value ?? $matches[0]; // Return the value or the original placeholder if not found
        }, $content);
    }
}
