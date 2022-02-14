<?php

namespace Database\Seeders;

use App\Models\Windowsills\Windowsill;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class WindowsillSeeder extends Seeder
{
    use WithFaker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setUpFaker();
        $this->seedWindowsillColors();
        $this->seedWindowsillMaterial();
        $this->seedWindowsillColorMaterial();
        Windowsill::factory()->count(20)->create();
        $this->seedWindowsillPrices();
    }

    protected function seedWindowsillColors() {
        for ($i = 0; $i < 5; $i++) {
            \DB::table('windowsills_colors')
                ->insert([
                    'name' => $this->faker->colorName(),
                    'link' => $this->faker->imageUrl(),
                ]);
        }
    }

    protected function seedWindowsillMaterial() {
        for ($i = 0; $i < 5; $i++) {
            \DB::table('windowsills_materials')
                ->insert([
                    'name' => $this->faker->word() . 'Material',
                    'link' => $this->faker->url(),
                ]);
        }
    }

    protected function seedWindowsillColorMaterial() {
        for ($i = 0; $i < 20; $i++) {
            \DB::table('windowsills_material_color')
                ->insert([
                    'material_id' => $this->faker->numberBetween(1, \DB::table('windowsills_materials')->count()),
                    'color_id' => $this->faker->numberBetween(1, \DB::table('windowsills_colors')->count()),
                    'name' => $this->faker->word() . 'color-material',
                    'link' => $this->faker->url(),
                ]);
        }
    }

    protected function seedWindowsillPrices() {
        for ($i = 1; $i <= \DB::table('windowsills')->count(); $i++) {
            \DB::table('windowsills_prices')
                ->insert([
                    'windowsill_id' => $i,
                    'width' => $this->faker->numberBetween(500, 2000),
                    'price' => $this->faker->randomFloat(1, 1000, 2000),
                    'real_price' => $this->faker->randomFloat(1, 1000, 2000) * 2,
                ]);
        }
        for ($i = 1; $i <= \DB::table('windowsills')->count() * 2; $i++) {
            \DB::table('windowsills_prices')
                ->insert([
                    'windowsill_id' => $this->faker->numberBetween(1, \DB::table('windowsills')->count()),
                    'width' => $this->faker->numberBetween(500, 2000),
                    'price' => $this->faker->randomFloat(1, 1000, 2000),
                    'real_price' => $this->faker->randomFloat(1, 1000, 2000) * 2,
                ]);
        }
    }
}
