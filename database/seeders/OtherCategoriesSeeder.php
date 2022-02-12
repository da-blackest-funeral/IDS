<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class OtherCategoriesSeeder extends Seeder
{
    use WithFaker;
    /**
     * Run the database seeds.
     * todo сделать сидеры для остальных таблиц
     * @return void
     */
    public function run() {
        $this->setUpFaker();
        $this->seedWraps();
        $this->seedSlopes();

    }

    protected function seedWraps() {

        for ($i = 0; $i < 5; $i++) {
            \DB::table('wraps_width')
                ->insert(['width' => random_int(100, 200)]);
        }

        for ($i = 0; $i < 7; $i++) {
            \DB::table('wraps_services')
                ->insert([
                    'montage_price' => $this->faker->numberBetween(300, 500),
                    'dismantling_price' => $this->faker->numberBetween(200, 400),
                ]);
        }

        for ($i = 0; $i < 10; $i++) {
            \DB::table('wraps')
                ->insert([
                    'name' => $this->faker->word(),
                    'url' => $this->faker->url(),
                    'img' => $this->faker->imageUrl(),
                    'calc_show' => $this->faker->boolean(90),
                    'catalog_show' => $this->faker->boolean(90),
                    'sort' => random_int(0, 10),
                    'description' => $this->faker->text(),
                    'category_id' => 4,
                    'wraps_service_id' => $this->faker->numberBetween(1, \DB::table('wraps_services')->count()),
                ]);
        }
    }

    protected function seedSlopes() {

        for ($i = 0; $i < 5; $i++) {
            \DB::table('slopes_width')
                ->insert(['width' => random_int(100, 200)]);
        }

        for ($i = 0; $i < 5; $i++) {
            \DB::table('slopes_colors')
                ->insert([
                    'name' => $this->faker->colorName(),
                ]);
        }

        for ($i = 0; $i < 10; $i++) {
            \DB::table('slopes')
                ->insert([
                    'name' => $this->faker->word(),
                    'sort' => random_int(0, 10),
                    'status' => $this->faker->boolean(80),
                    'category_id' => 4,
                ]);
        }

        for ($i = 0; $i < 20; $i++) {
            \DB::table('slopes_prices')
                ->insert([
                    'color_id' => random_int(1, \DB::table('slopes_colors')->count()),
                    'width_id' => random_int(1, \DB::table('slopes_width')->count()),
                    'price' => random_int(400, 800),
                ]);
        }


        \DB::table('slopes_montage_prices')
            ->insert([
                'slope_id' => random_int(1, \DB::table('slopes')->count()),
                'width_id' => random_int(1, \DB::table('slopes_width')->count()),
                'price' => random_int(200, 500),
            ]);
    }
}
