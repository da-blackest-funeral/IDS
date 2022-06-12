<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            $tables = [
                'glazed_windows_layers',
                'glazed_windows',
                'glazed_windows_additional',
            ];

            foreach ($tables as $table) {
                foreach (jsonData(database_path("data/$table")) as $jsonDatum) {
                    \DB::table($table)->insert($jsonDatum);
                }
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            //
        }
    };
