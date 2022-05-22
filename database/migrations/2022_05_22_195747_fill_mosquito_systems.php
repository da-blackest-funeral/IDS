<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {

        protected static array $tables = [
            'tissues',
            'groups',
            'types',
            'profiles',
            'additional',
            'products',
            'product_additional',
            'type_additional',
            'type_salary',
        ];

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            foreach (self::$tables as $table) {
                $data = jsonData(database_path("data/mosquito_systems_$table"));

                foreach ($data as $datum) {
                    DB::table("mosquito_systems_$table")->insert($datum);
                }
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            foreach (self::$tables as $table) {
                DB::table($table)->delete();
            }
        }
    };
