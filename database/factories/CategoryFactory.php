<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (!Category::first()) {
            return [
                'name' => $this->faker->word(),
            ];
        } else {
            return [
                'name' => $this->faker->word,
                'parent_id' => $this->faker->numberBetween(1, Category::max('id')),
            ];
        }
    }
}
