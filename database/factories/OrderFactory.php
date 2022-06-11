<?php

    namespace Database\Factories;

    use App\Models\Order;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use JetBrains\PhpStorm\ArrayShape;

    /**
     * @extends Factory
     */
    class OrderFactory extends Factory
    {
        protected $model = Order::class;

        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition() {
            return [
                'user_id' => 1,
                'comment' => $this->faker->word(),
                'delivery' => 600,
                'additional_visits' => 0,
                'kilometres' => 0,
                'need_delivery' => 1,
                'price' => rand(1700, 5000),
                'installer_id' => 2,
                'discount' => 0,
                'status' => 0,
                'measuring' => 1,
                'measuring_price' => 600,
                'discounted_measuring_price' => 0,
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 1,
                'taken_sum' => 0,
                'installing_difficult' => 1,
                'is_private_person' => 0,
                'done_status' => 0,
                'is_company_car' => 0,
                'prepayment' => 0,
                'installing_is_done' => 0,
                'structure' => '',
            ];
        }
    }
