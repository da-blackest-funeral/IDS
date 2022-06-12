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
        foreach (jsonData(database_path('data/system_variables')) as $variable) {
            \DB::table('system_variables')->insert($variable);
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
        DB::table('system_variables')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
