<?php

namespace Database\Factories\MosquitoSystems;

use App\Models\MosquitoSystems\Tissue;
use Illuminate\Database\Eloquent\Factories\Factory;

class TissueFactory extends Factory
{
    protected $model = Tissue::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word() . 'Tissue',
            'link_page' => $this->faker->domainName(),
            'description' => $this->faker->text(),
            'cut_width' => $this->faker->randomFloat(1, 0.1, 2.0),
        ];
    }
}
