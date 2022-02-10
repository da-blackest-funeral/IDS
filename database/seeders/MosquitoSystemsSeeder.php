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
        Product::factory()->count(20)->create();
    }
}
