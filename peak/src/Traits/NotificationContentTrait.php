<?php

namespace Qoraiche\Peak\Traits;

use App\Models\NotificationTemplate;
use Exception;

trait NotificationContentTrait
{
    /**
     * Get notification content from database with placeholders replaced.
     *
     * @param string $slug
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function getNotificationContent(string $slug, array $data = []): array
    {
        // Get the current language dynamically using app()->getLocale()
        $language = app()->getLocale();

        // Get the notification template for the given language, fallback to default if not found
        $template = NotificationTemplate::query()->where('slug', $slug)
            ->where('language', $language)
            ->first();

        if ($template) {
            // Replace placeholders in the title and body
            $title = $this->replacePlaceholders($template->title, $data);
            $body = $this->replacePlaceholders($template->body, $data);

            return [
                'title' => $title,
                'body' => $body
            ];
        }

        throw new Exception("Notification template for '{$slug}' not found in language '{$language}'");
    }

    /**
     * Replace placeholders in content with actual values.
     *
     * @param string $content
     * @param array $data
     * @return string
     */
    protected function replacePlaceholders(string $content, array $data = []): string
    {
        // Replace placeholders in the content
        foreach ($data as $key => $value) {
            // Support both dot notation (nested arrays) and object properties
            $content = preg_replace_callback('/\{{2}\s*([a-zA-Z0-9_.]+)\s*\}{2}/', function ($matches) use ($data) {
                $keys = explode('.', $matches[1]);
                $tempData = $data;
                foreach ($keys as $key) {
                    if (isset($tempData[$key])) {
                        $tempData = $tempData[$key];
                    } else {
                        return $matches[0]; // Return the placeholder as is if not found
                    }
                }
                return $tempData;
            }, $content);
        }

        return $content;
    }
}
