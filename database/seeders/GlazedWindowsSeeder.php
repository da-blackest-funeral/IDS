<?php

namespace Database\Seeders;

use App\Models\GlazedWindows\CamerasWidth;
use App\Models\GlazedWindows\GlazedWindows;
use App\Models\GlazedWindows\WithHeating;
use Database\Factories\GlazedWindows\GlazedWindowsFactory;
use Illuminate\Database\Seeder;

class GlazedWindowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->seedLayers();
        CamerasWidth::factory()->count(15)->create();
        GlazedWindows::factory()->count(15)->create();
        WithHeating::factory()->count(15)->create();
    }

    protected function seedLayers() {
        \DB::table('glazed_windows_layers')
            ->insert([
                [
                    'name' => 'Камера'
                ],
                [
                    'name' => 'Стекло'
                ],
            ]);
    }
}
