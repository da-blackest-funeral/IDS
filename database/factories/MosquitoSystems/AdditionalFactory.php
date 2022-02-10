<?php

namespace Database\Factories\MosquitoSystems;

use App\Models\MosquitoSystems\Additional;
use App\Models\MosquitoSystems\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionalFactory extends Factory
{
    protected $model = Additional::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'link' => $this->faker->domainName(),
            'group_id' => $this->faker->numberBetween(1, Group::max('id')),
        ];
    }
}
