<?php

    namespace Database\Seeders;

    use App\Models\Order;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;

    class OrderSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run() {
            Order::factory(30000)->create();
        }
    }
