<?php

namespace Database\Seeders;

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
        $this->seedFor('profiles');
        $this->seedFor('additional');

//        Additional::factory()->count(150)->create();
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
