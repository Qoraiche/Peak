<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Qoraiche\Peak\Models\DocCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DocCategoryFactory extends Factory
{
    protected $model = DocCategory::class;

    public function definition(): array
    {
        $nameEn = $this->faker->words(2, true);
        $nameFr = $this->faker->words(2, true);

        $slugEn = \Str::slug($nameEn);
        $slugFr = \Str::slug($nameFr);

        return [
            'name' => [
                'en' => $nameEn,
                'fr' => $nameFr,
            ],
            'slug' => [
                'en' => \Str::slug($nameEn),
                'fr' => \Str::slug($nameFr),
            ],
        ];
    }
}
