<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_asset' => strtoupper(fake()->bothify('ASSET-###')),
            'nama_asset' => fake()->words(2, true),
            'gambar' => 'assets/images/default.jpg', // default image path
            'deskripsi' => fake()->paragraph(),
            'category_id' => Category::factory(), // This will create a category if none exists
        ];
    }

    /**
     * Define a state for assets with specific category
     */
    public function withCategory(Category $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    /**
     * Define a state for assets without image
     */
    public function withoutImage(): static
    {
        return $this->state(fn(array $attributes) => [
            'gambar' => null,
        ]);
    }
}
