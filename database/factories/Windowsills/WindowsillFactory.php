<?php

namespace Database\Factories\Windowsills;

use App\Models\Category;
use App\Models\Windowsills\Windowsill;
use Illuminate\Database\Eloquent\Factories\Factory;

class WindowsillFactory extends Factory
{
    protected $model = Windowsill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name' => $this->faker->word(),
            'category_id' => $this->faker->numberBetween(1, Category::count()),
            'material_color_id' => $this->faker->numberBetween(
                1,
                \DB::table('windowsills_material_color')
                    ->count()
            ),
            'plug_price' => $this->faker->numberBetween(100, 400),
            'price_docking_profile' => $this->faker->numberBetween(200, 600),
            'sort' => $this->faker->numberBetween(1, 20),
            'status' => $this->faker->boolean(90)
        ];
    }
}
