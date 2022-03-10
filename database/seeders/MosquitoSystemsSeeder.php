<?php

namespace Database\Seeders;

use App\Models\MosquitoSystems\Additional;
use App\Models\MosquitoSystems\Group;
use App\Models\MosquitoSystems\Product;
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
        $this->seedFor('tissues');
        $this->seedFor('groups');
        $this->seedFor('types');
//        Group::factory()->count(10)->create();
//        Additional::factory()->count(150)->create();
//        Tissue::factory()->count(50)->create();
//        Profile::factory()->count(50)->create();
//        Type::factory()->count(20)->create();
//        Product::factory()->count(350)->create();
//        $this->seedProductAdditional(250);
//        $this->seedTypeAdditional(250);
//        $this->seedTypeGroup(300);
    }

    protected function seedFor(string $configKey) {
        foreach (config("mosquito_systems.$configKey") as $item) {
            $item['created_at'] = date('Y-m-d H:i:s', time());
            $item['updated_at'] = date('Y-m-d H:i:s', time());

            \DB::table("mosquito_systems_$configKey")->insert($item);
        }
    }
}
