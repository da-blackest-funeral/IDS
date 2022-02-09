<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $prefix = 'windowsills';

    protected function comment(string $table, string $comment) {
        DB::statement("ALTER TABLE $table comment '$comment'");
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("{$this->prefix}_materials", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_materials", 'Материал(тип) подоконника');

        Schema::create("{$this->prefix}_colors", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_material_color", function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')
                ->constrained("{$this->prefix}_materials");
            $table->foreignId('color_id')
                ->constrained("{$this->prefix}_colors");
            $table->string('name');
            $table->string('link')->nullable();
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_material_color", 'Конфигурация цвет-материал');

        Schema::create("{$this->prefix}", function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->foreignId('material_color_id')
                ->constrained("{$this->prefix}_material_color");
            $table->float('plug_price')
                ->comment('Цена заглушки');
            $table->float('price_docking_profile')
                ->comment('Цена стыковочного профиля');
            $table->integer('sort');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_prices", function (Blueprint $table) {
            $table->id();
            $table->foreignId('windowsill_id')
                ->constrained("{$this->prefix}");
            $table->integer('width');
            $table->float('price');
            $table->float('real_price')->comment('Не знаю что это');
            $table->timestamps();
        });
        $this->comment("{$this->prefix}_prices", 'Цена конфигурации подоконника в зависимости от ширины');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("{$this->prefix}_materials");
        Schema::dropIfExists("{$this->prefix}_colors");
        Schema::dropIfExists("{$this->prefix}_material_color");
        Schema::dropIfExists("{$this->prefix}");
        Schema::dropIfExists("{$this->prefix}_prices");
    }
};
