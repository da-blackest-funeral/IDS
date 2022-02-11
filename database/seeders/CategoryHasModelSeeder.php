<?php

namespace Database\Seeders;

use App\Models\MosquitoSystems\Slope;
use App\Models\MosquitoSystems\Tissue;
use App\Models\MosquitoSystems\Wrap;
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
                'model' => Tissue::class,
            ],
            [
                'category_ids' => range(14, 18) + [19, 21, 23],
                'model' => TypesWindows::class,
            ],
            [
                'category_ids' => [24],
                'model' => Slope::class,
            ],
            [
                'category_ids' => [20, 25],
            ],
            [
                'category_ids' => [22],
                'model' => Wrap::class,
            ],
        ];
        foreach ($relations as $relation) {
            foreach ($relation['category_ids'] as $id) {
                \DB::table('category_has_model')
                    ->insert([
                        'category_id' => $id,
                        'model' => $relation['model'] ?? '',
                    ]);
            }
        }
    }
}
