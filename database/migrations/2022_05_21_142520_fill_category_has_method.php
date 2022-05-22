<?php

    use Illuminate\Database\Migrations\Migration;

    return new class extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            $relations = jsonData(database_path('data/categories_methods'));
            foreach ($relations as $relation) {
                foreach ($relation['category_ids'] as $id) {
                    \DB::table('category_has_method')
                        ->insert([
                            'category_id' => $id,
                            'method' => $relation['method'] ?? '',
                        ]);
                }
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            DB::table('category_has_method')->delete();
        }
    };
