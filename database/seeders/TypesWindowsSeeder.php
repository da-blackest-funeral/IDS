<?php

namespace Database\Seeders;

use App\Models\TypesWindows;
use Illuminate\Database\Seeder;

class TypesWindowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypesWindows::factory()->count(10)->create();
    }
}
