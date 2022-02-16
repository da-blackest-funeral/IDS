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
        $this->seedGroups();
        CamerasWidth::factory()->count(15)->create();
        GlazedWindows::factory()->count(15)->create();
        WithHeating::factory()->count(15)->create();
        $this->seedTemperatureControllers();
        $this->seedGlazedWindowsWithHeatingWidth();
    }

    protected function seedGlazedWindowsWithHeatingWidth() {
        foreach ([9, 10, 12, 14, 16, 20, 24] as $item) {
            \DB::table('glazed_windows_with_heating_width')
                ->insert([
                    'width' => $item
                ]);
        }
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

    protected function seedTemperatureControllers() {
        \DB::table('temperature_controllers')
            ->insert([
               'name' => 'RTR-E 6163 (белый)',
               'description' => 'Электромеханический терморегулятор с выключателем',
                'amperage' => '16A',
                'temperature-range' => '+5...+30C',
                'price' => 2600
            ]);
        \DB::table('temperature_controllers')
            ->insert([
                'name' => 'TH-0343SA (белый)',
                'description' => 'Терморегулятор электронный со встроенным датчиком',
                'amperage' => '16A',
                'temperature-range' => '+5...+30C',
                'price' => 6500
            ]);
    }

    protected function seedGroups() {
        foreach (['Нагреваемые СПО', 'Нагреваемые СПД', 'Нагреваемый триплекс', 'Нагреваемое стекло'] as $group) {
            \DB::table('glazed_windows_groups')
                ->insert([
                    'name' => $group
                ]);
        }
    }
}
