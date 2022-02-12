<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Other\Slope;
use App\Models\Other\Wrap;
use App\Models\TypesWindows;
use Illuminate\Database\Seeder;

class CategoryHasModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $array = range(14, 18);
        array_push($array, 19, 21, 23);
        $relations = [
            [
                'category_ids' => range(5, 13),
                'method' => Category::class . '::tissues',
            ],
            [
                // [19, 21, 23]
                'category_ids' => $array,
                'method' => TypesWindows::class . '::all',
            ],
            [
                'category_ids' => [24],
                'method' => Slope::class . '::all',
            ],
            [
                'category_ids' => [20, 25],
            ],
            [
                'category_ids' => [22],
                'method' => Wrap::class . '::all',
            ],
        ];
        foreach ($relations as $relation) {
            foreach ($relation['category_ids'] as $id) {
                \DB::table('category_has_model')
                    ->insert([
                        'category_id' => $id,
                        'method' => $relation['method'] ?? '',
                    ]);
            }
        }
    }
}
