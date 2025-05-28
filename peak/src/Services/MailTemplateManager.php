<?php

namespace Qoraiche\Peak\Services;

use Qoraiche\Peak\Models\MailTemplate;

class MailTemplateManager
{
    /**
     * Get the template by slug and language.
     *
     * @param string $slug
     * @param string|null $language
     * @return MailTemplate|null
     */
    public function getTemplate(string $slug, ?string $language = null): ?MailTemplate
    {
        return MailTemplate::query()->where('slug', $slug)
            ->where('language', $language ?? app()->getLocale())
            ->first();
    }

    /**
     * @param $language
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTemplatesByLanguage($language = null)
    {
        return MailTemplate::query()->select(['language',
            'id',
            'slug',
            'fields',
            'subject',
            'from_name',
            'name',
            'from_email',
            'body',
            'active'
        ])->where('language', $language ?? app()->getLocale())
            ->get();
    }
}
