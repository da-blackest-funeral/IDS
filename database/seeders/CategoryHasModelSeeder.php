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
        $relations = [
            [
                'category_ids' => range(5, 13),
                'method' => Category::class . '::tissues',
            ],
            [
                'category_ids' => range(14, 18) + [19, 21, 23],
                'method' => TypesWindows::class,
            ],
            [
                'category_ids' => [24],
                'method' => Slope::class,
            ],
            [
                'category_ids' => [20, 25],
            ],
            [
                'category_ids' => [22],
                'method' => Wrap::class,
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
