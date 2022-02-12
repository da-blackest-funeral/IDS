<?php

namespace Database\Seeders;

use App\Models\MosquitoSystems\Additional;
use App\Models\MosquitoSystems\Group;
use App\Models\MosquitoSystems\Product;
use App\Models\MosquitoSystems\Profile;
use App\Models\MosquitoSystems\Tissue;
use App\Models\MosquitoSystems\Type;
use Illuminate\Database\Seeder;

class MosquitoSystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Group::factory()->count(10)->create();
        Additional::factory()->count(100)->create();
        Tissue::factory()->count(50)->create();
        Profile::factory()->count(50)->create();
        Type::factory()->count(20)->create();
        Product::factory()->count(100)->create();
        $this->seedProductAdditional(150);
        $this->seedTypeAdditional(150);
        $this->seedTypeGroup(30);
    }

    protected function seedProductAdditional(int $times) {
        for ($i = 0; $i < $times; $i++) {
            \DB::table('mosquito_systems_product_additional')
                ->insert([
                    'product_id' => random_int(1, Product::count()),
                    'additional_id' => random_int(1, Additional::count()),
                ]);
        }
    }

    protected function seedTypeAdditional(int $times) {
        for ($i = 0; $i < $times; $i++) {
            \DB::table('mosquito_systems_type_additional')
                ->insert([
                    'type_id' => random_int(1, Type::count()),
                    'additional_id' => random_int(1, Additional::count()),
                    'price' => random_int(500, 1000),
                ]);
        }
    }

    protected function seedTypeGroup(int $times) {
        for ($i = 0; $i < $times; $i++) {
            \DB::table('mosquito_systems_type_group')
                ->insert([
                    'type_id' => random_int(1, Type::count()),
                    'group_id' => random_int(1, Group::count()),
                    'sort' => random_int(1, 100),
                ]);
        }
    }
}
