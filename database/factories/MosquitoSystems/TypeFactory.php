<?php

namespace Database\Factories\MosquitoSystems;

use App\Models\Category;
use App\Models\MosquitoSystems\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeFactory extends Factory
{
    protected $model = Type::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word() . 'Type',
            'category_id' => $this->faker->numberBetween(
                Category::whereNotNull('parent_id')->min('id'),
                Category::whereNotNull('parent_id')->max('id')
            ),
            'yandex' => 'https://yandex-market/' . $this->faker->word(),
            'page_link' => $this->faker->domainName(),
            'measure_link' => $this->faker->domainName(),
            'salary' => $this->faker->numberBetween(300, 5000),
            'price' => $this->faker->numberBetween(300, 2000),
            'description' => $this->faker->text(),
            'img' => $this->faker->imageUrl(),
            'measure_time' => $this->faker->numberBetween(1, 5),
            'delivery' => 500,
        ];
    }
}
