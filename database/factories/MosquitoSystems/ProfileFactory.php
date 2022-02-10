<?php

namespace Database\Factories\MosquitoSystems;

use App\Models\MosquitoSystems\Profile;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'name' => $this->faker->word() . 'Profile',
            'service_id' => $this->faker->numberBetween(1, Service::max('id')),
        ];
    }
}
