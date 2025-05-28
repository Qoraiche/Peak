<?php

namespace Qoraiche\Peak\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

class ThemeService
{
    /**
     * @return string
     */
    public function getCurrentThemeName(): string
    {
        return current_theme_name();
    }

    /**
     * Check if a theme exists by its folder name.
     *
     * @param string $themeName
     * @return bool
     * @throws FileNotFoundException
     */
    public function themeExists(string $themeName): bool
    {
        // Get all themes
        $themes = $this->getAllThemes();

        // Check if any theme matches the folder name
        return collect($themes)->contains(function ($theme) use ($themeName) {
            return $theme['folder'] === $themeName;
        });
    }

    /**
     * Get all themes from the Themes directory with active status.
     *
     * @return array
     * @throws FileNotFoundException
     */

    /**
     * Get all themes from the Themes directory with active status.
     *
     * @return array
     * @throws FileNotFoundException
     */
    public function getAllThemes(): array
    {
        $themesDirectory = resource_path('js/Themes');
        $themes = [];

        if (!File::exists($themesDirectory)) {
            return $themes;
        }

        // Get all theme.json files
        $themeFiles = File::glob($themesDirectory . '/*/theme.json');

        $currentThemeName = current_theme_name(); // Get the current theme name (folder name)

        foreach ($themeFiles as $filePath) {
            $folderName = basename(dirname($filePath)); // Extract folder name

            $themeData = json_decode(File::get($filePath), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $themeData['folder'] = $folderName; // Add the folder name for reference
                $themeData['is_active'] = strtolower($folderName) === strtolower($currentThemeName); // Case-sensitive check
                $themes[] = $themeData;
            }
        }

        // Sort themes so active themes appear on top
        usort($themes, function ($a, $b) {
            return $b['is_active'] <=> $a['is_active']; // Descending order by is_active
        });

        return $themes;
    }
}
