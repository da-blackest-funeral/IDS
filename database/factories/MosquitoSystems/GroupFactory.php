<?php

namespace Database\Factories\MosquitoSystems;

use App\Models\MosquitoSystems\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word() . 'Group',
        ];
    }
}
