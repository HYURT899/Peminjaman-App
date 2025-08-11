<?php

namespace Database\Factories;

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
            'gambar' => 'images/cctv.jpg',
            'deskripsi' => fake()->sentence(),
            'stok' => fake()->numberBetween(1, 5)
        ];
    }
}
