<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $prefix = 'wraps';

    protected function comment(string $table, string $comment) {
        DB::statement("ALTER TABLE $table comment '$comment'");
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("{$this->prefix}_width", function (Blueprint $table) {
            $table->id();
            $table->integer('width');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_services", function (Blueprint $table) {
            $table->id();
            $table->float('montage_price');
            $table->float('dismantling_price');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_services", 'Цены за монтаж\демонтаж пленок');

        Schema::create("{$this->prefix}", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('img')->nullable();
            $table->boolean('calc_show')->default(true);
            $table->boolean('catalog_show')->default(true);
            $table->integer('sort');
            $table->text('description');
            $table->foreignId('wraps_service_id')
                ->constrained("{$this->prefix}_services");
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}", 'Основная информация о пленках');

        Schema::create("{$this->prefix}_types", function (Blueprint $table) {
            $table->id();
            $table->foreignId('wrap_id')
                ->constrained("{$this->prefix}");
            $table->foreignId('width_id')
                ->constrained("{$this->prefix}_width");
            $table->string('name');
            $table->foreignId('service_id')
                ->constrained('services');
            $table->string('img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("{$this->prefix}_width");
        Schema::dropIfExists("{$this->prefix}_services");
        Schema::dropIfExists("{$this->prefix}");
        Schema::dropIfExists("{$this->prefix}_types");
    }
};
