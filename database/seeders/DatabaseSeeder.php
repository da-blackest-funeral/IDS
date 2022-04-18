<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call([
            SystemVariablesSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            TypesWindowsSeeder::class,
            MosquitoSystemsSeeder::class,
            CategoryHasMethodSeeder::class,
            OtherCategoriesSeeder::class,
            GlassSeeder::class,
            WindowsillSeeder::class,
            GlazedWindowsSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
        ]);
    }
}
