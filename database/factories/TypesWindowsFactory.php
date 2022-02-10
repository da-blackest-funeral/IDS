<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TypesWindowsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'sort' => $this->faker->numberBetween(1, 100),
        ];
    }
}
