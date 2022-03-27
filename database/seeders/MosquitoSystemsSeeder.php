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
        $this->seedFor('products');
        $this->seedFor('product_additional');
        $this->seedFor('type_additional');
        $this->seedFor('type_salary');
    }

    protected function seedFor(string $configKey) {

        foreach (MosquitoSystemsData::all($configKey) as $item) {
            $item['created_at'] = date('Y-m-d H:i:s', time());
            $item['updated_at'] = date('Y-m-d H:i:s', time());

            \DB::table("mosquito_systems_$configKey")->insert($item);
        }
    }
}
