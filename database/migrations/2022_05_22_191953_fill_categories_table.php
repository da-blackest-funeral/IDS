<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (jsonData(database_path('data/categories')) as $superCategory) {
            \DB::table('categories')->insert($superCategory);
        }
        foreach (jsonData(database_path('data/subcategories')) as $subCategory) {
            \DB::table('categories')->insert($subCategory);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('categories')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
