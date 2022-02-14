<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected function comment(string $table, string $comment) {
        DB::statement("ALTER TABLE $table comment '$comment'");
    }

    protected $prefix = 'glazed_windows';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("{$this->prefix}_groups", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_groups", 'Нагреваемое стекло, нагреваемое СПО и т.д.');

        Schema::create("{$this->prefix}_with_heating", function (Blueprint $table) {
            $table->id();
            $table->float('price');
            $table->integer('cameras');
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_with_heating", 'Стеклопакет с подогревом');

        Schema::create("{$this->prefix}_services", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        $this->comment(
            "{$this->prefix}_services",
            'Виды услуг, связанных со стеклопакетами - монтаж, демонтаж и т.д.'
        );

        Schema::create("{$this->prefix}_type_service", function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_window_id')
                ->constrained('types_windows');
            $table->foreignId("{$this->prefix}_service_id")
                ->constrained("{$this->prefix}_services");
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->float('price');
            $table->timestamps();
        });
        $this->comment(
            "{$this->prefix}_type_service",
            'Цена определяется двумя параметрами - тип окна и вид услуги'
        );

        Schema::create("{$this->prefix}_layers", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_layers", 'Камера или стекло');

        Schema::create("{$this->prefix}_cameras_width", function (Blueprint $table) {
            $table->id();
            $table->integer('width');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_cameras_width", 'Все значения ширины камеры');

        Schema::create("{$this->prefix}_additional", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort');
            $table->float('price');
            $table->foreignId('layer_id')
                ->constrained("{$this->prefix}_layers");
            $table->timestamps();
        });
        $this->comment(
            "{$this->prefix}_additional",
            'Дополнительные опции для стеклопакетов - если нужен аргон или алюминиевая рамка'
        );

        Schema::create("{$this->prefix}", function (Blueprint $table) {
            $table->id();
            $table->foreignId('layer_id')
                ->constrained("{$this->prefix}_layers");
            $table->foreignId('width_id')
                ->constrained("{$this->prefix}_cameras_width");
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->float('price');
            $table->integer('sort');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}", 'Цена на стеклопакет определяется шириной камеры');

        Schema::create("glass", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->integer('sort');
            $table->string('thickness')->comment('Толщина');
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->timestamps();
        });
        $this->comment('glass', 'Стекло');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("{$this->prefix}_groups");
        Schema::dropIfExists("{$this->prefix}_with_heating");
        Schema::dropIfExists("{$this->prefix}_services");
        Schema::dropIfExists("{$this->prefix}_type_service");
        Schema::dropIfExists("{$this->prefix}_layers");
        Schema::dropIfExists("{$this->prefix}_cameras_width");
        Schema::dropIfExists("{$this->prefix}_additional");
        Schema::dropIfExists("{$this->prefix}");
        Schema::dropIfExists('glass');
    }
};
