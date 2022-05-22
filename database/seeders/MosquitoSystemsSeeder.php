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
//        $this->seedFor('tissues');
//        $this->seedFor('groups');
//        $this->seedFor('types');
//        $this->seedFor('profiles');
//        $this->seedFor('additional');
//        $this->seedFor('products');
//        $this->seedFor('product_additional');
//        $this->seedFor('type_additional');
//        $this->seedFor('type_salary');
    }

    protected function seedFor(string $configKey) {

//        foreach (MosquitoSystemsData::all($configKey) as $item) {
//            if ($configKey == 'products' && isset($item['price'])) {
//                $item['price'] *= 1.2 * 1.1;
//                $item['price'] = ceil($item['price']);
//            }
//
//            if ($configKey == 'type_additional' && isset($item['price'])) {
//                $item['price'] *= 1.1;
//                $item['price'] = ceil($item['price']);
//            }
//
//            if ($configKey == 'types' && isset($item['delivery'])) {
//                $item['delivery'] *= 1.2;
//            }
//
//            $item['created_at'] = date('Y-m-d H:i:s', time());
//            $item['updated_at'] = date('Y-m-d H:i:s', time());
//
//            $new[] = $item;
//        }
//
//        file_put_contents(database_path("data/mosquito_systems_$configKey.json"), json_encode($new,
//            JSON_UNESCAPED_UNICODE), FILE_APPEND);
//    }
    }
}
