<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $prefix = 'overlays';

    protected function comment(string $table, string $comment) {
        DB::statement("ALTER TABLE $table comment '$comment'");
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("{$this->prefix}_plugs", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('service_id')
                ->constrained('services');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_plugs", 'Заглушки для накладок');

        Schema::create("{$this->prefix}_sizes", function (Blueprint $table) {
            $table->id();
            $table->integer('length');
            $table->integer('width');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_colors", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}", function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')
                ->constrained("{$this->prefix}_colors");
            $table->foreignId('size_id')
                ->constrained("{$this->prefix}_sizes");
            $table->float('price');
            $table->float('montage_price');
            $table->foreignId('service_id');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}", 'Цена определяется конфигурацией цвет-размер');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("{$this->prefix}_plugs");
        Schema::dropIfExists("{$this->prefix}_sizes");
        Schema::dropIfExists("{$this->prefix}_colors");
        Schema::dropIfExists("{$this->prefix}");
    }
};
