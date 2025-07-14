<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryIds = Category::pluck('id')->toArray();
        $warehouseIds = Warehouse::pluck('id')->toArray();
        $unitIds = \App\Models\Unit::pluck('id')->toArray();

        return [
            'code' => fake()->unique()->word,
            'name' => fake()->word,
            'unit_id' => fake()->randomElement($unitIds),
            'category_id' => fake()->randomElement($categoryIds),
            'warehouse_id' => fake()->randomElement($warehouseIds),
            'barcode' => fake()->unique()->ean13,
            'min_stock' => fake()->numberBetween(1, 100),
            'current_stock' => fake()->numberBetween(1, 100),
        ];
    }
}
