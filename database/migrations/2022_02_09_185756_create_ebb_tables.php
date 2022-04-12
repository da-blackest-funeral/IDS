<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $prefix = 'ebb'; // Не знаю как это перевести)

//    protected function comment(string $table, string $comment) {
//        DB::statement("ALTER TABLE $table comment '$comment'");
//    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("{$this->prefix}", function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->string('name');
            $table->float('plug_price')->comment('Цена заглушки');
            $table->timestamps();
        });
//        $this->comment("{$this->prefix}", 'Таблица отливов');

        Schema::create("{$this->prefix}_width", function (Blueprint $table) {
            $table->id();
            $table->integer('width');
            $table->timestamps();
        });

        Schema::create("{$this->prefix}_prices", function (Blueprint $table) {
            $table->id();
            $table->foreignId('ebb_id')
                ->constrained("{$this->prefix}");
            $table->foreignId('width_id')
                ->constrained("{$this->prefix}_width");
            $table->float('price');
            $table->timestamps();
        });
//        $this->comment("{$this->prefix}_prices", 'Цена определяется типом отлива и шириной');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("{$this->prefix}");
        Schema::dropIfExists("{$this->prefix}_width");
        Schema::dropIfExists("{$this->prefix}_prices");
    }
};
