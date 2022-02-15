<?php

namespace Database\Factories\GlazedWindows;

use App\Models\Category;
use App\Models\GlazedWindows\CamerasWidth;
use App\Models\GlazedWindows\GlazedWindows;
use App\Models\GlazedWindows\Layer;
use Illuminate\Database\Eloquent\Factories\Factory;

class GlazedWindowsFactory extends Factory
{
    protected $model = GlazedWindows::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'layer_id' => $this->faker->numberBetween(1, Layer::count()),
            'width_id' => $this->faker->numberBetween(1, CamerasWidth::count()),
            'category_id' => 14,
            'price' => $this->faker->numberBetween(1000, 5000),
            'sort' => $this->faker->numberBetween(1, 20)
        ];
    }
}
