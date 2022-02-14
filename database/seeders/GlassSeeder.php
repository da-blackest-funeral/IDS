<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class GlassSeeder extends Seeder
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
        for ($i = 0; $i < 10; $i++) {
            \DB::table('glass')
                ->insert([
                    'name' => $this->faker->word() . 'Glass',
                    'price' => $this->faker->numberBetween(100, 500),
                    'sort' => $this->faker->numberBetween(1, 20),
                    'category_id' => $this->faker->numberBetween(1, Category::count()),
                    'thickness' => $this->faker->randomFloat(2, 3, 5) . 'мм.'
                ]);
        }
    }
}
