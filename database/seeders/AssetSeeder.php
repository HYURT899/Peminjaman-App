<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories first
        $categories = Category::factory(3)->create();

        // Create 5 assets for each category
        $categories->each(function ($category) {
            Asset::factory(5)->withCategory($category)->create();
        });
    }
}
