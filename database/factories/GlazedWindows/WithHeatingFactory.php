<?php

namespace Database\Factories\GlazedWindows;

use App\Models\GlazedWindows\Group;
use App\Models\GlazedWindows\WithHeating;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithHeatingFactory extends Factory
{
    protected $model = WithHeating::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_id' => $this->faker->numberBetween(1, Group::count()),
            'price' => $this->faker->numberBetween(8000, 15000),
            'name' => $this->faker->word,
            'cameras' => $this->faker->numberBetween(1, 2),
            'category_id' => 17,
        ];
    }
}
