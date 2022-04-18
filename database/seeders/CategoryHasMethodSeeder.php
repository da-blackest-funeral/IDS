<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Slopes\Slope;
use App\Models\TypesWindows;
use App\Models\Wraps\Wrap;
use App\Services\Helpers\MosquitoSystemsHelper;
use Illuminate\Database\Seeder;

class CategoryHasMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $array = range(15, 19);
        array_push($array, 20, 22, 24);
        $relations = [
            [
                'category_ids' => range(5, 14),
                'method' => MosquitoSystemsHelper::class . '::tissues',
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
                \DB::table('category_has_method')
                    ->insert([
                        'category_id' => $id,
                        'method' => $relation['method'] ?? '',
                    ]);
            }
        }
    }
}
