<?php

namespace Database\Seeders;

use App\Models\GlazedWindows\CamerasWidth;
use App\Models\GlazedWindows\GlazedWindows;
use App\Models\GlazedWindows\WithHeating;
use Database\Factories\GlazedWindows\GlazedWindowsFactory;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class GlazedWindowsSeeder extends Seeder
{
    use WithFaker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
//        $this->seedFor('types_windows');
        $this->seedFor('glazed_windows_layers');
        $this->seedFor("glazed_windows");
    }

    protected function seedFor($name) {
        foreach (GlazedWindowsData::all($name) as $item) {
            $item['created_at'] = date('Y-m-d H:i:s', time());
            $item['updated_at'] = date('Y-m-d H:i:s', time());
//            $item['category_id'] = ;

            \DB::table($name)->insert($item);
        }
    }
}
