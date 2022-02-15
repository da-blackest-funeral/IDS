<?php

namespace Database\Factories\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\Factory;

class CamerasWidthFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'width' => $this->faker->randomFloat(2, 3, 6)
        ];
    }
}
