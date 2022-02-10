<?php

namespace Database\Factories\MosquitoSystems;

use App\Models\Category;
use App\Models\MosquitoSystems\Product;
use App\Models\MosquitoSystems\Profile;
use App\Models\MosquitoSystems\Tissue;
use App\Models\MosquitoSystems\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type_id' => $this->faker->numberBetween(1, Type::max('id')),
            'tissue_id' => $this->faker->numberBetween(1, Tissue::max('id')),
            'profile_id' => $this->faker->numberBetween(1, Profile::max('id')),
            'category_id' => $this->faker->numberBetween(1, Category::max('id')),
            'price' => $this->faker->numberBetween(500, 2000)
        ];
    }
}
