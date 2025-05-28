<?php

namespace Qoraiche\Peak\Services\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Http\Filters\Admin\LanguageSearchFilter;
use Qoraiche\Peak\Models\Language;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\TranslationLoader\LanguageLine;

class LanguageService
{
    /**
     * @param Request $request
     * @param $perPage
     * @param string $searchQueryParam
     * @return LengthAwarePaginator
     */
    public function getSearchablePaginatedLanguages(Request $request, $perPage, string $searchQueryParam = 'search')
    {
        $limitPagination = (int)$request->input('limit', $perPage);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = $perPage;
        }

        return QueryBuilder::for(Language::class)->allowedFilters([
            AllowedFilter::custom($searchQueryParam, new LanguageSearchFilter)
        ])->allowedSorts([
            Language::ID_COLUMN_NAME,
            Language::NAME_COLUMN_NAME,
            Language::CODE_COLUMN_NAME,
            Language::DEFAULT_COLUMN_NAME,
            Language::CREATED_AT,
            Language::UPDATED_AT,
        ])->orderBy(Language::ORDER_COLUMN_NAME, 'asc')
            ->paginate($limitPagination);
    }

    /**
     * @param string $langCode
     * @param string $group
     * @param $searchTerm
     * @return array
     */
    public function getLanguageTranslations(string $langCode, string $group, $searchTerm = null): array
    {
        $translationsGroup = collect(LanguageLine::query()->where('group', $group)->get())
            ->mapWithKeys(function (LanguageLine $line) use ($langCode) {
                $text = $line->text[$langCode]
                    ?? collect($line->text)->first() // fallback to any existing translation
                    ?? null;
                return [$line->key => $text];
            });

        if ($searchTerm) {
            $translationsGroup = $translationsGroup->filter(function ($value, $key) use ($searchTerm) {
                $keyMatches = stripos($key, $searchTerm) !== false;
                $valueString = is_array($value) ? json_encode($value) : (string)$value;
                $valueMatches = stripos($valueString, $searchTerm) !== false;

                return $keyMatches || $valueMatches;
            });
        }

        return $translationsGroup->all();
    }

    /**
     * @param array $translationLines
     * @param string $languageCode
     * @param string $group
     * @return void
     */
    public function addOrUpdateTranslationLines(array $translationLines, string $languageCode, string $group): void
    {
        foreach ($translationLines as $key => $value) {
            $this->addOrUpdateTranslationLine($group, $key, $value, $languageCode);
        }
    }

    /**
     * @param $group
     * @param $key
     * @param $newValue
     * @param $languageCode
     * @return void
     */
    public function addOrUpdateTranslationLine($group, $key, $newValue, $languageCode): void
    {
        // Find the existing language line by key
        $languageLine = LanguageLine::where('group', $group)->where('key', $key)->first();

        if (!$languageLine) {
            // If the language line doesn't exist, you can create it
            $languageLine = LanguageLine::create([
                'group' => $group, // Replace with your group name
                'key' => $key,
                'text' => [$languageCode => $newValue], // Set the new value for the specified language
            ]);
        } else {
            // If the language line exists, check if the language code exists in the text array
            if (isset($languageLine->text[$languageCode])) {
                // If it exists, update its value
                $languageLine->setTranslation($languageCode, $newValue);
            } else {
                // If it doesn't exist, add a new entry for the specified language
                $languageLine->setTranslation($languageCode, $newValue);
            }

            $languageLine->save();
        }
    }

    /**
     * @param string $name
     * @param string $code
     * @param string $direction
     * @param bool $active
     * @param string|null $emoji
     * @return void
     */
    public function addNewLanguage(string $name, string $code, string $direction, bool $active, ?string $emoji = '🏳️')
    {
        Language::query()->create([
            Language::NAME_COLUMN_NAME => Str::title($name),
            Language::DEFAULT_COLUMN_NAME => Language::count() < 1,
            Language::CODE_COLUMN_NAME => strtolower($code),
            Language::DIRECTION_COLUMN_NAME => $direction,
            Language::ACTIVE_COLUMN_NAME => $active,
            Language::FLAG_EMOJI_COLUMN_NAME => $emoji
        ]);
    }

    /**
     * @param Language $language
     * @param string $name
     * @param string $code
     * @param string $direction
     * @param bool $active
     * @param string|null $emoji
     * @return void
     */
    public function updateLanguage(Language $language, string $name, string $code, string $direction, bool $active, ?string $emoji = '🏳️'): void
    {
        $language->update([
            Language::NAME_COLUMN_NAME => $name,
            Language::CODE_COLUMN_NAME => $code,
            Language::DIRECTION_COLUMN_NAME => $direction,
            Language::ACTIVE_COLUMN_NAME => $active,
            Language::FLAG_EMOJI_COLUMN_NAME => $emoji
        ]);
    }

    /**
     * @param Language $language
     * @return void
     */
    public function deleteLanguage(Language $language)
    {
        $language->delete();
    }

    /**
     * @param $group
     * @param $key
     * @return void
     */
    public function deleteLanguageLine($group, $key)
    {
        if ($language = LanguageLine::query()
            ->where('group', $group)
            ->where('key', $key)->first()) {
            $language->delete();
        }
    }

    /**
     * @param string|null $code
     * @return array|null
     */
    public function getLanguageByLocaleCode(string $code = null)
    {
        return Language::query()->where('code', $code ?? Helpers::getLocale())
            ->first();
    }
}
