<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Spatie\TranslationLoader\LanguageLine;

class ImportTranslationsFromJson extends Command
{
    /** @var string */
    protected $signature = 'peak:import-translations {locale?}';

    /** @var string */
    protected $description = 'Import all translations from lang/*.json and resources/lang/*/*.php into the database';

    /**
     * @return void
     */
    public function handle(): void
    {
        $locale = $this->argument('locale');

        $this->importJsonTranslations($locale);
        $this->importPhpTranslations($locale);

        $this->info('✅ Import finished.');
    }

    /**
     * @param string|null $locale
     * @return void
     */
    protected function importJsonTranslations(?string $locale = null): void
    {
        $baseLangPath = base_path('lang');
        $resourcesLangPath = resource_path('lang');

        $langPath = File::exists($baseLangPath) ? $baseLangPath : $resourcesLangPath;

        $files = File::glob("$langPath/*.json");

        $imported = 0;

        foreach ($files as $file) {
            $fileLocale = pathinfo($file, PATHINFO_FILENAME);

            if ($locale && $locale !== $fileLocale) {
                continue;
            }

            $translations = json_decode(File::get($file), true);

            if (!is_array($translations)) {
                continue;
            }

            foreach ($translations as $key => $value) {
                $line = LanguageLine::firstOrNew([
                    'group' => '*',
                    'key' => $key,
                ]);

                $text = $line->text ?? [];
                $text[$fileLocale] = $value;
                $line->text = $text;
                $line->save();

                $imported++;
            }
        }

        $this->info("📄 Imported {$imported} JSON translation lines.");
    }

    /**
     * @param string|null $locale
     * @return void
     */
    protected function importPhpTranslations(?string $locale = null): void
    {
        $baseLangPath = base_path('lang');
        $resourcesLangPath = resource_path('lang');

        $langPath = File::exists($baseLangPath) ? $baseLangPath : $resourcesLangPath;

        $directories = File::directories($langPath);

        $imported = 0;

        foreach ($directories as $dir) {
            $dirLocale = basename($dir);

            if ($locale && $locale !== $dirLocale) {
                continue;
            }

            $files = File::allFiles($dir);

            foreach ($files as $file) {
                $group = $file->getFilenameWithoutExtension();

                $translations = Lang::getLoader()->load($dirLocale, $group);

                $flattened = $this->flattenArray($translations);

                foreach ($flattened as $key => $value) {
                    $line = LanguageLine::firstOrNew([
                        'group' => $group,
                        'key' => $key,
                    ]);

                    $text = $line->text ?? [];
                    $text[$dirLocale] = $value;
                    $line->text = $text;
                    $line->save();

                    $imported++;
                }
            }
        }

        $this->info("📂 Imported {$imported} PHP translation lines.");
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result += $this->flattenArray($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
