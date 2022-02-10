<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected function comment(string $table, string $comment) {
        DB::statement("ALTER TABLE $table comment '$comment'");
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')
                ->comment('Ссылка из подкатегории на супер-категорию')
                ->nullable();
            $table->timestamps();
        });

        Schema::create('types_windows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort');
            $table->timestamps();
        });
        $this->comment('types_windows', 'Типы окон - Алюминиевые, окна из ПВХ и т.д.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('services');
        Schema::dropIfExists('types_windows');
    }
};
