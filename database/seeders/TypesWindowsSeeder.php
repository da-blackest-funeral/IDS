<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypesWindowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->seed();
    }

    protected function seed() {
        foreach (GlazedWindowsData::all('types_windows') as $item) {
            $item['created_at'] = date('Y-m-d H:i:s', time());
            $item['updated_at'] = date('Y-m-d H:i:s', time());

            \DB::table("types_windows")->insert($item);
        }
    }
}
